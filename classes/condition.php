<?php

/**
 * Languages configuration for the availability_registrationdate plugin.
 *
 * @package   availability_registrationdate
 * @copyright 2025 Deloviye ludi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_registrationdate;

use context_course;
use core_availability\info;
use stdClass;

class condition extends \core_availability\condition {
    /**
     * @var int $relativenumber Определяет количество относительно:
     * сколько единиц измерения использовать (например, 3 дня, 2 недели).
     */
    private $relativenumber;

    /**
     * @var int $relativedwm Определяет единицу измерения для времени:
     * 0 - минуты,
     * 1 - часы,
     * 2 - дни,
     * 3 - недели,
     * 4 - месяцы.
     */
    private $relativedwm;

    /**
     * Конструктор для класса condition, инициализирующий объект условия
     * доступности на основе относительных временных параметров.
     * @param stdClass $structure результат декодирования JSON, содержащий конфигурацию условия.
     *                  'n' - Указывает кол-во единиц ()
     *                  'd' - Единица времени, в которой производится рассчёт (см. relativedwm)
     */
    public function __construct($structure) {
        $this->relativenumber = property_exists($structure, 'n') ? intval($structure->n) : 1;
        $this->relativedwm = property_exists($structure, 'd') ? intval($structure->d) : 2;
    }

    /**
     * Сохраняет текущие настройки условия в виде объекта для дальнейшего использования в БД.
     * Готовит к созданию JSON
     *
     * @return \stdClass объект, содержащий текущую конфигурацию условия для записей состояния
     */
    public function save() {
        return (object)[
            'type' => 'relativedate',
            'n' => intval($this->relativenumber),
            'd' => intval($this->relativedwm),
        ];
    }

    /**
     * Определяет, доступен ли элемент пользователю в текущий момент.
     * Вычисляет временное значение через calc().
     * Если значение равно 0, элемент считается недоступным.
     * Доступ определяется сравнением текущего
     * времени с вычисленным значением и, при необходимости, инверсией результата.
     *
     * @param bool $not Если true, инвертирует результат доступности
     * @param info $info Объект, содержащий информацию о курсе
     * @param bool $grabthelot Зарезервировано для будущего использования
     * @param int $userid ID пользователя, для которого проверяется доступность
     * @return bool true, если элемент доступен пользователю, иначе false
     */
    public function is_available($not, info $info, $grabthelot, $userid) {
        $calc = $this->calc($info->get_course(), $userid);

        // Нет доступа, если не удалось извлечь значение
        if ($calc === 0) {
            return false;
        }

        $allow = time() > $calc;
        if ($not) {
            $allow = !$allow;
        }
        return $allow;
    }

    /**
     * Возвращает строку, описывающую ограничения для указанного элемента,
     * независимо от их текущего применения.
     * Определяет, какие ограничения применяются к элементу на основе курса и данных пользователя.
     * В зависимости от флага $not, определяет, использовать ли 'from' или 'until'.
     * Возвращает отформатированную дату ограничения с дополнительной отладочной информацией.
     *
     * @param bool $full Если true, возвращает полную информацию об ограничениях
     * @param bool $not Если true, инвертирует условия описания
     * @param info $info Объект, содержащий информацию об элементе, который проверяется
     * @return string Строка с информацией для администрирования об ограничениях данного элемента
     */
    public function get_description($full, $not, info $info): string {
        global $USER;
        $course = $info->get_course();
		$frut = $not ? 'until' : 'from';
        $calc = $this->calc($course, $USER->id);
        if ($calc === 0) {
            $a = $this->get_debug_string();
            return trim(get_string('admin_' . $frut, 'availability_registrationdate', $a));
        }
        $a = userdate($calc, get_string('strftimedatetime', 'langconfig'));
        return trim(get_string($frut, 'availability_registrationdate', $a));
    }

    /**
     * Obtains a representation of the options of this condition as a string for debugging.
     *
     * @return string Text representation of parameters
     */

    /**
     * Возвращает строковое представление параметров этого условия для отладки.
     * Создаёт строку со значением относительного кол-ва времени и единицы измерения.
     *
     * @return string Текстовое представление параметров для отладки.
     * @throws \coding_exception
     */
    protected function get_debug_string() {
        return ' ' . $this->relativenumber . ' ' . get_string(self::option_dwm($this->relativedwm),'availability_registrationdate');
    }

    /**
     * Возвращает строковое представление единицы времени на основе заданного индекса.
     *
     * Метод принимает целочисленный индекс и возвращает строку, представляющую соответствующую
     * единицу времени — минуту, час, день, неделю или месяц.
     * Если индекс не совпадает с ожидаемыми значениями, возвращает пустую строку.
     *
     * @param int $i Индекс единицы времени.
     * @return string Строка, обозначающая единицу времени, или пустая строка, если индекс не распознан.
     */
    public static function option_dwm(int $i): string {
        switch ($i) {
            case 0:
                return 'minute';
            case 1:
                return 'hour';
            case 2:
                return 'day';
            case 3:
                return 'week';
            case 4:
                return 'month';
        }
        return '';
    }

    /**
     * Вычисляет относительное время на основе заданных параметров курса и пользователя.
     *
     * Метод определяет дату и время, используемые для проверки доступности элементов курса,
     * на основе заданных относительных параметров. Рассчитывает дату в зависимости от даты регистрации пользователя на сайте.
     *
     * @param stdClass $course Объект курса, содержащий информацию о текущем курсе.
     * @param int $userid Идентификатор пользователя, для которого производится вычисление.
     * @return int Относительная дата в формате Unix-времени. Возвращает 0, если вычисление невозможно.
     *
     * @throws \dml_exception
     */
    private function calc($course, $userid): int {
        $a = $this->relativenumber;
        $b = $this->option_dwm($this->relativedwm);
        $x = "$a $b";

		// After latest enrolment start date.
		$sql = 'SELECT u.timecreated
				FROM {user} u
				WHERE u.id = :userid AND u.deleted <> 1';

		$reg_date = $this->get_value_from_db($sql, ['userid' => $userid]);
		return $this->fixdate("+$x", $reg_date);
    }

    /**
     * Извлекает запись из базы данных на основе SQL-запроса.
     *
     * Выполняет SQL-запрос с заданными параметрами и извлекает одну запись из
     * результата, игнорируя возможные дубликаты. Полученная запись преобразуется в
     * массив, из которого извлекается значение первого элемента.
     *
     * @param string $sql SQL-запрос для выполнения, который должен выбирать конкретное значение.
     * @param array $parameters Массив параметров для подстановки в SQL-запрос.
     * @return int Наименьшее найденное значение в записи. Возвращает 0, если запись не найдена.
     * @throws \dml_exception
     */
    private function get_value_from_db($sql, $parameters): int {
        global $DB;
        if ($reg_date = $DB->get_record_sql($sql, $parameters, IGNORE_MULTIPLE)) {
            $record = get_object_vars($reg_date);
            return array_shift($record);
        }
        return 0;
    }


    /**
     * Корректирует дату, прибавляя или вычитая заданное количество времени, и возвращает новое время.
     *
     * Метод принимает строку, представляющую временной интервал, который будет добавлен к заданной
     * дате. Вычисляет новую дату равную дате регистрации пользователя + установленное временное ограничение.
     *
     * @param string $calc Строка, описывающая временной интервал и оператор ('+' или '-'), например,
     *                     '+2 days' или '-3 weeks'.
     * @param int $newdate Исходная дата, с которой производится вычитание или сложение, в формате Unix-времени.
     * @return int Новое значение даты в формате Unix-времени. Возвращает 0, если исходная дата (`$newdate`)
     *             не является положительной.
     */
    private function fixdate($calc, $newdate): int {
        if ($newdate > 0) {
            $olddate = strtotime($calc, $newdate);
            if ($this->relativedwm > 1) {
                $arr1 = getdate($olddate);
                $arr2 = getdate($newdate);
                return mktime($arr2['hours'], $arr2['minutes'], $arr2['seconds'], $arr1['mon'], $arr1['mday'], $arr1['year']);
            }
            return $olddate;
        }
        return 0;
    }
}

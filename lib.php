<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version info.
 *
 * @package mod_regrestrict
 * @copyright 2024 Deloviye ludi
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

//Функция, возвращающая массив чисел от 1 до count
function get_number_array($count) {
    $result_array = array();
    for($i = 1; $i <= $count; $i++) {
        $result_array[$i] = $i;
    }
    return $result_array;
}

/**
 * Функция для проверки доступа к курсу на основе даты регистрации пользователя.
 *
 * @param int $userid Идентификатор пользователя
 * @param int $courseid Идентификатор курса
 * @return bool true, если доступ разрешен, иначе false
 */
function can_access_course($userid, $courseid) {
    global $DB;

    $user = $DB->get_record('user', ['id' => $userid], 'id, timecreated', MUST_EXIST);
    $registration_date = $user->timecreated;
    $current_date = time();
    $course_settings = $DB->get_record('local_plugin_course_access', ['courseid' => $courseid]);
    if (!$course_settings) {
        return false;
    }
    $days_since_registration = ($current_date - $registration_date) / 86400;
    return $days_since_registration >= $course_settings->mindays;
}

/**
 * Пример использования функции для фильтрации курсов.
 */
function get_available_courses($userid) {
    global $DB;
    $courses = $DB->get_records('course', null, 'fullname');
    $available_courses = [];
    foreach ($courses as $course) {
        if (can_access_course($userid, $course->id)) {
            $available_courses[] = $course;
        }
    }

    return $available_courses;
}

/**
 * Добавляем элемент меню для отображения доступных курсов.
 */
function local_myplugin_extend_navigation(global_navigation $navigation) {
    global $USER;

    $node = $navigation->add(get_string('availablecourses', 'local_myplugin'));

    $available_courses = get_available_courses($USER->id);
    foreach ($available_courses as $course) {
        $node->add($course->fullname, new moodle_url('/course/view.php', ['id' => $course->id]));
    }
}

/**
 * Установочный скрипт для создания таблицы настроек курса.
 */
function xmldb_local_myplugin_install() {
    global $DB;

    $dbman = $DB->get_manager();

    // Создаем таблицу для хранения настроек курса.
    $table = new xmldb_table('local_plugin_course_access');

    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('mindays', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

    $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

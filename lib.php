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
    $course_settings = $DB->get_record('mod_regrestrict', ['courseid' => $courseid]);
    if (!$course_settings) {
        return false;
    }
    $date = strtotime("$course_settings->timenumber $course_settings->timeunit", $registration_date);
    $days_since_registration = ($current_date - $registration_date) / 86400;
    return $days_since_registration >= $date;
}

/**
 * Установочный скрипт для создания таблицы настроек курса.
 */
function xmldb_local_myplugin_install() {
    global $DB;

    $dbman = $DB->get_manager();

    // Создаем таблицу для хранения настроек курса.
    $table = new xmldb_table('mod_regrestrict');

    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('timenumber', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('timeunit', XMLDB_TYPE_TEXT, null, XMLDB_NOTNULL, null, null);

    $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

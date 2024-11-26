<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_open_course_materials_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2021051702) {
        $dbman = $DB->get_manager();

        // Добавляем поле 'access_number_of_time' в таблицу курса.
        $table = new xmldb_table('course');
        $field1 = new xmldb_field('access_number_of_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'summary');
        $field2 = new xmldb_field('access_unit_of_time', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'summary');

        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        upgrade_plugin_savepoint(true, 2021051702, 'local', 'open_course_materials');
    }

    return true;
}

<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_open_course_by_registration_date', get_string('pluginname', 'local_open_course_by_registration_date'));

    require_once(__DIR__.'/lib.php');

    $settings->add(new admin_setting_configselect(
        'local_open_course_by_registration_date/course_access_number_of_time_units',
        get_string('course_access_number', 'local_open_course_by_registration_date'),
        get_string('course_access_number_desc', 'local_open_course_by_registration_date'),
        1,
        get_days_array(30)
    ));

    $settings->add(new admin_setting_configselect(
        'local_open_course_by_registration_date/course_access_unit_of_time',
        get_string('course_access_unit_of_time', 'local_open_course_by_registration_date'),
        get_string('course_access_unit_of_time_desc', 'local_open_course_by_registration_date'),
        'days',
        array(
            'minutes' => get_string('minute', 'local_open_course_by_registration_date'),
            'hours' => get_string('hour', 'local_open_course_by_registration_date'),
            'days' => get_string('day', 'local_open_course_by_registration_date'),
            'weeks' => get_string('week', 'local_open_course_by_registration_date'),
            'months' => get_string('month', 'local_open_course_by_registration_date'),
        )
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_open_course_by_registration_date/enable',
        get_string('enable', 'local_open_course_by_registration_date'),
        get_string('enable_desc', 'local_open_course_by_registration_date'),
        0
    ));

    $ADMIN->add('localplugins', $settings);
}



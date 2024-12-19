<?php
namespace mod_regrestrict\hook;

use MoodleQuickForm;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../lib.php');

class after_form_definition {
    /**
     * Метод для добавления новой вкладки в форму редактирования курса.
     *
     * @param MoodleQuickForm $mform Форма редактирования курса.
     * @param stdClass $data Данные формы.
     */
    public function execute($mform, $data) {
        global $DB;

        $mform->addElement('header','regrestrict', get_string('title', 'regrestrict'));
        $mform->addElement('selectyesno', 'enablerestriction', get_string('enable', 'regrestrict'));
        $number_time = get_number_array(30);
        $mform->addElement('select', 'timenumber', get_string('time_number', 'regrestrict'), $number_time);
        $mform->addHelpButton('timenumber', 'time_number', 'regrestrict');
        $unit_time = array(
            'minute' => get_string('minute', 'regrestrict'),
            'hour' => get_string('hour', 'regrestrict'),
            'day' => get_string('day', 'regrestrict'),
            'week' => get_string('week', 'regrestrict'),
            'month' => get_string('month', 'regrestrict'),
        );
        $mform->addElement('select', 'timeunit', get_string('time_unit', 'regrestrict'), $unit_time);
        $mform->addHelpButton('timeunit', 'time_unit', 'regrestrict');
    }
}
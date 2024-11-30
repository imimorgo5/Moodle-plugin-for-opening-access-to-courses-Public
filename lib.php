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
 * @package local_open_course_by_registration_date
 * @copyright 2024 Deloviye ludi
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function get_days_array($days_count) {
    $result_array = array();
    for($i = 1; $i <= $days_count; $i++) {
        $result_array[$i] = $i;
    }
    return $result_array;
}

function local_open_course_by_registration_date_can_access_course($courseid) {
    global $USER, $DB;
    $user_registration_date = $DB->get_field('user', 'timecreated', ['id' => $USER->id]);
    $course_access_number = $DB->get_field('course', 'access_number_of_time', ['id' => $courseid]);
    $course_access_unit = $DB->get_field('course', 'access_unit_of_time', ['id' => $courseid]);
    if ($course_access_number === null || $course_access_unit === null) {
        $course_access_number = get_config('local_open_course_by_registration_date', 'course_access_number_of_time_units') ?? 1;
        $course_access_unit = get_config('local_open_course_by_registration_date', 'course_access_unit_of_time') ?? 'days';
    }
    $course_availability_date = strtotime("+{$course_access_number} {$course_access_unit}", $user_registration_date);
    return time() >= $course_availability_date;
}
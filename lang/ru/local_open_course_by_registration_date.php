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

$string['pluginname'] = 'Открытие доступа к курсам в зависимости от даты регистрации на сайте';
$string['course_access_number'] = 'Количество единиц измерения времени';
$string['course_access_number_desc'] = 'Количество единиц измерения времени с момента регистрации пользователя до открытия доступа к курсам';
$string['course_access_unit_of_time'] = 'Единица измерения времени';
$string['course_access_unit_of_time_desc'] = 'Единица измерения времени, применяемая к количеству единиц измерения времени, выбранному выше';
$string['enable'] = 'Включить/отключить плагин';
$string['enable_desc'] = 'Включить или отключить задержку доступа пользователя к курсам после регистрации';
$string['minute'] = 'Минута';
$string['hour'] = 'Час';
$string['day'] = 'День';
$string['week'] = 'Неделя';
$string['month'] = 'Месяц';
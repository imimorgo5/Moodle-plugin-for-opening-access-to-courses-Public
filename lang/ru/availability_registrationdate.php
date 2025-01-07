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
 * Languages configuration for the availability_registrationdate plugin.
 *
 * @package   availability_registrationdate
 * @copyright 2024 Deloviye ludi
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['description'] = 'Открывайте материалы в зависимости от даты регистрации пользователя на сайте.';
$string['pluginname'] = 'Ограничение по дате регистрации на сайте';
$string['title'] = 'Ограничение по дате регистрации на сайте';
$string['privacy:metadata'] = 'Плагин "Ограничение по дате регистрации на сайте" не хранит никаких персональных данных.';
$string['description_before'] = 'Прошло(-ёл, -ла) ';
$string['description_after'] = ' после даты регистрации на сайте.';
$string['day'] = 'день(-я,-ей)';
$string['hour'] = 'час(-а,-ов)';
$string['minute'] = 'минут(-а,-ы)';
$string['month'] = 'месяц(-а,-ев)';
$string['week'] = 'неделя(-ли,-ль)';
$string['year'] = 'год(-а,лет)';
$string['until'] = 'Установлено ограничение, связанное с датой и временем регистрации на сайте. Материал будет доступен до {$a->rnumber} {$a->rtime} {$a->rela}';
$string['from'] = 'Установлено ограничение, связанное с датой и временем регистрации на сайте. Материал станет доступен с {$a->rnumber} {$a->rtime} {$a->rela}';
$string['admin_until'] = 'Прошло не более {$a} с момента регистрации на сайте.';
$string['admin_from'] = 'Прошло более {$a} с момента регистрации на сайте.';

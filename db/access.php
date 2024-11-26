<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/open_course_by_registration_date:manage' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW
        ],
    ],
];

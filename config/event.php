<?php

return [
    'badge' => env('EVENT_BADGE', 'Event Invitation'),
    'title' => env('EVENT_TITLE', "You're Invited"),
    'description' => env(
        'EVENT_DESCRIPTION',
        'Join us for an exceptional gathering with colleagues and friends. An evening of connection, celebration, and memorable moments.',
    ),
    'date_label' => env('EVENT_DATE_LABEL', 'December 31, 2026'),
    'time_label' => env('EVENT_TIME_LABEL', '5:00 PM - 10:00 PM'),
    'location' => env('EVENT_LOCATION', 'Grand Ballroom'),
    'starts_at' => env('EVENT_STARTS_AT', '2026-12-31T17:00:00'),
];

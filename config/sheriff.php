<?php

return [
    'host' => env('SHERIFF_HOST'),
    'external_hosts' => env('SHERIFF_EXTERNAL_HOSTS'),
    'theme_color' => env('SHERIFF_THEME_COLOR', '#e30712'),
    'logo_url' => env('SHERIFF_LOGO_URL', null),
    'registration_disabled' => env('SHERIFF_REGISTRATION_DISABLED', false),
    'hide_birth_date_at_checkin' => env('SHERIFF_HIDE_BIRTH_DATE_AT_CHECKIN', false),
    'hide_manual_checkin_after_seconds' => env('SHERIFF_HIDE_MANUAL_CHECKIN_AFTER_SECONDS', 0), // 0 = disabled
    'privacy_url' => env('SHERIFF_PRIVACY_URL', null), // null = Use privacy policy from strings.yml
    'imprint_url' => env('SHERIFF_IMPRINT_URL', null), // null = No imprint URL
];

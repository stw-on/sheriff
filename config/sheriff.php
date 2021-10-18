<?php

return [
    'host' => env('SHERIFF_HOST'),
    'external_hosts' => env('SHERIFF_EXTERNAL_HOSTS'),
    'theme_color' => env('SHERIFF_THEME_COLOR', '#e30712'),
    'logo_url' => env('SHERIFF_LOGO_URL', null),
    'registration_disabled' => env('SHERIFF_REGISTRATION_DISABLED', false),
];

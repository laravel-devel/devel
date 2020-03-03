<?php

return [
    'root' => [
        'default_email' => 'root@example.com',
        'default_password' => 'qwerty',

        // This is a debug option used to prevent anyone from editing the main
        // root's credentials on the live demo site
        'is_locked' => env('ROOT_LOCKED', false),
    ],
];

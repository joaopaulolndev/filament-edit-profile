<?php

return [
    'locale_column' => 'locale',
    'theme_color_column' => 'theme_color',
    'avatar_column' => 'avatar_url',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public',
    'media' => [
        'collection' => 'avatar',
    ],
    'redirectUrl' => '/admin/edit-profile',
];

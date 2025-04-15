<?php

return [
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public',
    'media' => [
        'collection' => 'avatar',
    ],
    'redirectUrl' => '/admin/edit-profile',
];

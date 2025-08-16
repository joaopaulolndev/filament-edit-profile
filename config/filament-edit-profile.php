<?php

return [
    'locales' => [
        'pt_BR' => '🇧🇷 Português',
        'en' => '🇺🇸 Inglês',
        'es' => '🇪🇸 Espanhol',
    ],
    'locale_column' => 'locale',
    'avatar_column' => 'avatar_url',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public', // or replace by filesystem disk visibility with fallback value
];

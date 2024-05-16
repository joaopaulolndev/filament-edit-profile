<?php

namespace Joaopaulolndev\FilamentEditProfile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Joaopaulolndev\FilamentEditProfile\FilamentEditProfile
 */
class FilamentEditProfile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Joaopaulolndev\FilamentEditProfile\FilamentEditProfile::class;
    }
}

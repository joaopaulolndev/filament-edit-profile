<?php

namespace NoopStudios\FilamentEditProfile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NoopStudios\FilamentEditProfile\FilamentEditProfile
 */
class FilamentEditProfile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \NoopStudios\FilamentEditProfile\FilamentEditProfile::class;
    }
}

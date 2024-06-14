<?php

namespace Joaopaulolndev\FilamentEditProfile\Concerns;

use Exception;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

trait HasUser
{
    public $user;

    protected function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception(__('filament-edit-profile::default.user_load_error'));
        }

        return $user;
    }
}

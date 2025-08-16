<?php

namespace Joaopaulolndev\FilamentEditProfile\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetUserLocale
{
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = Auth::guard($guard)->user();
        $locale = config('filament-edit-profile.locale_column', 'locale');

        if ($user && filled($user->getAttributeValue($locale))) {
            App::setLocale($user->getAttributeValue($locale));
        }

        return $next($request);
    }
}

<?php

namespace Joaopaulolndev\FilamentEditProfile\Http\Middleware;

use Closure;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetUserThemeColor
{
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = Auth::guard($guard)->user();
        $theme_color = config('filament-edit-profile.theme_color_column', 'theme_color');

        if ($user && filled($user->getAttributeValue($theme_color))) {
            FilamentColor::register([
                'primary' => $user->getAttributeValue($theme_color),
            ]);
        }

        return $next($request);
    }
}

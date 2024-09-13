<?php

namespace Joaopaulolndev\FilamentEditProfile\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Filament\Facades\Filament;

class TwoFactorFilamentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();
        if ($user->hasEnabledTwoFactorAuthentication()) {
            if (Session::has('two_factor_approved') && ! Session::get('two_factor_approved')) {
                if ($request->route()->getName() != 'two_factor_page' && $request->route()->getName() != 'filament.admin.auth.logout') {
                    return redirect()->route('two_factor_page');
                }
            }
        }

        if (! $user->hasEnabledTwoFactorAuthentication() && Session::has('two_factor_approved')) {
            Session::forget('two_factor_approved');
        }

        if ($user->hasEnabledTwoFactorAuthentication() && Session::get('two_factor_approved') && $request->route()->getName() === 'two_factor_page') {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        if (($request->route()->getName() === 'two_factor_page') && ! $user->hasEnabledTwoFactorAuthentication()) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        return $next($request);
    }
}

<?php

namespace NoopStudios\FilamentEditProfile\Http\Responses\FilamentEditProfile\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\EmailChangeVerificationResponse as Responsable;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use NoopStudios\FilamentEditProfile\Pages\EditProfilePage;

class EmailChangeVerificationResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->intended(EditProfilePage::getUrl() ?? Filament::getUrl());
    }
}

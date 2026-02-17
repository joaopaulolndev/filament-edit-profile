<?php

use Livewire\Livewire;

/**
 * Regression test for https://github.com/joaopaulolndev/filament-edit-profile/issues/134
 *
 * Livewire components must be registered in the ServiceProvider (which runs on
 * every request), not in Plugin::boot() (which only runs when the Filament
 * panel boots). Livewire update requests (POST /livewire/update) bypass Filament
 * panel middleware, so components registered only in Plugin::boot() are not
 * available and Livewire throws ComponentNotFoundException.
 */
describe('Livewire component registration', function () {
    it('registers edit_profile_form via the service provider', function () {
        expect(Livewire::exists('edit_profile_form'))->toBeTrue();
    });

    it('registers edit_password_form via the service provider', function () {
        expect(Livewire::exists('edit_password_form'))->toBeTrue();
    });

    it('registers delete_account_form via the service provider', function () {
        expect(Livewire::exists('delete_account_form'))->toBeTrue();
    });

    it('registers browser_sessions_form via the service provider', function () {
        expect(Livewire::exists('browser_sessions_form'))->toBeTrue();
    });

    it('registers custom_fields_form via the service provider', function () {
        expect(Livewire::exists('custom_fields_form'))->toBeTrue();
    });

    it('registers sanctum_tokens via the service provider', function () {
        expect(Livewire::exists('sanctum_tokens'))->toBeTrue();
    });

    it('registers multi_factor_authentication via the service provider', function () {
        expect(Livewire::exists('multi_factor_authentication'))->toBeTrue();
    });
});

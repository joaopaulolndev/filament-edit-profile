<?php

use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Livewire\BrowserSessionsForm;

/**
 * Regression test for the browser sessions form on a non-database session driver.
 *
 * The form used to be force-disabled whenever session.driver was not "database",
 * and getShouldShowBrowserSessionsForm() did that by overwriting the property,
 * so the getter was destructive. The form now stays enabled and only its session
 * list is swapped for a "list unavailable" message.
 */
describe('Browser sessions on a non-database session driver', function () {
    beforeEach(function () {
        config()->set('session.driver', 'file');
    });

    it('keeps the browser sessions form enabled on a non-database driver', function () {
        expect(FilamentEditProfilePlugin::make()->getShouldShowBrowserSessionsForm())->toBeTrue();
    });

    it('does not mutate the plugin state when reading the getter twice', function () {
        $plugin = FilamentEditProfilePlugin::make();

        expect($plugin->getShouldShowBrowserSessionsForm())->toBeTrue()
            ->and($plugin->getShouldShowBrowserSessionsForm())->toBeTrue();
    });

    it('still honours the shouldShowBrowserSessionsForm opt-out', function () {
        expect(
            FilamentEditProfilePlugin::make()
                ->shouldShowBrowserSessionsForm(false)
                ->getShouldShowBrowserSessionsForm()
        )->toBeFalse();
    });

    it('returns no sessions to list', function () {
        expect(BrowserSessionsForm::getSessions())->toBe([]);
    });
});

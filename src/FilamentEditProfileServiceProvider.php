<?php

namespace NoopStudios\FilamentEditProfile;

use Filament\Auth\Http\Responses\Contracts\EmailChangeVerificationResponse as EmailChangeVerificationResponseContract;
use Illuminate\Support\Facades\Route;
use Livewire\Features\SupportTesting\Testable;
use NoopStudios\FilamentEditProfile\Commands\FilamentEditProfileCommand;
use NoopStudios\FilamentEditProfile\Http\Responses\EmailChangeVerificationResponse;
use NoopStudios\FilamentEditProfile\Testing\TestsFilamentEditProfile;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentEditProfileServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-edit-profile';

    public static string $viewNamespace = 'filament-edit-profile';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('noopstudios/filament-edit-profile');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $this->app->bind(EmailChangeVerificationResponseContract::class, EmailChangeVerificationResponse::class);
    }

    public function packageBooted(): void
    {
        // Handle Stubs
        if (app()->runningInConsole()) {
            $publishMigration = function ($migrationFileName, $publishTag) {
                if (! $this->migrationFileExists($migrationFileName)) {
                    $this->publishes([
                        __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                    ], $publishTag);
                }
            };
            $publishMigration('add_custom_fields_to_users_table.php', 'filament-edit-profile-custom-field-migration');
            $publishMigration('add_locale_to_users_table.php', 'filament-edit-profile-locale-migration');
            $publishMigration('add_theme_color_to_users_table.php', 'filament-edit-profile-theme-color-migration');
        }

        // Testing
        Testable::mixin(new TestsFilamentEditProfile);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'NoopStudios/filament-edit-profile';
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentEditProfileCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'add_custom_fields_to_users_table',
            'add_avatar_url_to_users_table',
            'add_locale_to_users_table',
            'add_theme_color_to_users_table',
        ];
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path('migrations/*.php.stub')) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }

    protected static function registerRoutes(): void
    {
        if (app()->routesAreCached()) {
            return;
        }

        Route::middleware(['web', 'auth'])
            ->name('verification.email.')
            ->group(function () {
                Route::get('/email/change/verify', \NoopStudios\FilamentEditProfile\Http\Controllers\EmailChangeController::class)
                    ->name('change');
            });
    }
}

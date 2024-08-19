<?php

namespace Joaopaulolndev\FilamentEditProfile;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Joaopaulolndev\FilamentEditProfile\Commands\FilamentEditProfileCommand;
use Joaopaulolndev\FilamentEditProfile\Testing\TestsFilamentEditProfile;
use Livewire\Features\SupportTesting\Testable;
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
                    ->askToStarRepoOnGitHub('joaopaulolndev/filament-edit-profile');
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

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        // FilamentAsset::registerScriptData(
        //     $this->getScriptData(),
        //     $this->getAssetPackageName()
        // );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            $publishMigration = function ($migrationFileName, $publishTag) {
                if (! $this->migrationFileExists($migrationFileName)) {
                    $this->publishes([
                        __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                    ], $publishTag);
                }
            };
            $publishMigration('add_avatar_url_to_users_table.php', 'filament-edit-profile-avatar-migration');
            $publishMigration('add_custom_fields_to_users_table.php', 'filament-edit-profile-custom-field-migration');
        }

        // Testing
        Testable::mixin(new TestsFilamentEditProfile);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'Joaopaulolndev/filament-edit-profile';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-edit-profile', __DIR__ . '/../resources/dist/components/filament-edit-profile.js'),
            Css::make('filament-edit-profile-styles', __DIR__ . '/../resources/dist/filament-edit-profile.css'),
            // Js::make('filament-edit-profile-scripts', __DIR__ . '/../resources/dist/filament-edit-profile.js'),
        ];
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
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'add_custom_fields_to_users_table',
            'add_avatar_url_to_users_table',
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
}

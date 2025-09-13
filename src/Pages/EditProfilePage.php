<?php

namespace Joaopaulolndev\FilamentEditProfile\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Panel;

class EditProfilePage extends Page
{
    protected string $view = 'filament-edit-profile::filament.pages.edit-profile-page';

    protected static ?string $slug = 'edit-profile';

    public static function getSlug(?Panel $panel = null): string
    {
        if ($panel === null) {
            $panel = Filament::getCurrentOrDefaultPanel();
        }

        $plugin = $panel?->getPlugin('filament-edit-profile');

        $slug = $plugin?->getSlug();

        return $slug ? $slug : self::$slug;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getShouldRegisterNavigation();
    }

    public static function getNavigationSort(): ?int
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getSort();
    }

    public static function getNavigationIcon(): ?string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getIcon();
    }

    public static function getNavigationGroup(): ?string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationGroup();
    }

    public function getTitle(): string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getTitle() ?? __('filament-edit-profile::default.title');
    }

    public static function getNavigationLabel(): string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationLabel() ?? __('filament-edit-profile::default.title');
    }

    public static function canAccess(): bool
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getCanAccess();
    }

    public function getRegisteredCustomProfileComponents(): array
    {
        return filament('filament-edit-profile')->getRegisteredCustomProfileComponents();
    }
}

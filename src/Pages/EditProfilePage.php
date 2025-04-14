<?php

namespace Joaopaulolndev\FilamentEditProfile\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class EditProfilePage extends Page
{
    protected static string $view = 'filament-edit-profile::filament.pages.edit-profile-page';

    protected static ?string $slug = 'edit-profile';
    
    public ?string $activeTab = 'profile';

    public function getTabs(): array
    {
        $tabs = [];
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        if ($plugin->shouldShowEditProfileForm) {
            $tabs['profile'] = [
                'label' => __('filament-edit-profile::default.profile_information'),
                'icon' => 'heroicon-o-user',
            ];
        }

        if ($plugin->shouldShowEditPasswordForm) {
            $tabs['password'] = [
                'label' => __('filament-edit-profile::default.password'),
                'icon' => 'heroicon-o-key',
            ];
        }

        if ($plugin->getShouldShowDeleteAccountForm()) {
            $tabs['delete-account'] = [
                'label' => __('filament-edit-profile::default.delete_account'),
                'icon' => 'heroicon-o-trash',
            ];
        }

        if ($plugin->getShouldShowSanctumTokens()) {
            $tabs['api-tokens'] = [
                'label' => __('filament-edit-profile::default.api_tokens'),
                'icon' => 'heroicon-o-key',
            ];
        }

        if ($plugin->getShouldShowBrowserSessionsForm()) {
            $tabs['browser-sessions'] = [
                'label' => __('filament-edit-profile::default.browser_sessions'),
                'icon' => 'heroicon-o-computer-desktop',
            ];
        }

        if (config('filament-edit-profile.show_custom_fields') && ! empty(config('filament-edit-profile.custom_fields'))) {
            $tabs['custom-fields'] = [
                'label' => __('filament-edit-profile::default.custom_fields'),
                'icon' => 'heroicon-o-document-text',
            ];
        }

        return $tabs;
    }

    public static function getSlug(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        $slug = $plugin->getSlug();

        $slug = $slug ? $slug : self::$slug;

        return $slug;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getShouldRegisterNavigation();
    }

    public static function getNavigationSort(): ?int
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getSort();
    }

    public static function getNavigationIcon(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getIcon();
    }

    public static function getNavigationGroup(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationGroup();
    }

    public function getTitle(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getTitle() ?? __('filament-edit-profile::default.title');
    }

    public static function getNavigationLabel(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationLabel() ?? __('filament-edit-profile::default.title');
    }

    public static function canAccess(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getCanAccess();
    }

    public function getRegisteredCustomProfileComponents(): array
    {
        return filament('filament-edit-profile')->getRegisteredCustomProfileComponents();
    }
}

<?php

namespace Joaopaulolndev\FilamentEditProfile\Forms;

use Filament\Forms;

class EditProfileForm
{
    public static function get(): array
    {
        return [
            Forms\Components\Section::make(__('filament-edit-profile::default.profile_information'))
                ->aside()
                ->description(__('filament-edit-profile::default.profile_information_description'))
                ->schema([
                    Forms\Components\FileUpload::make('avatar_url')
                        ->label(__('filament-edit-profile::default.avatar'))
                        ->avatar()
                        ->imageEditor()
                        ->directory(filament('filament-edit-profile')->getAvatarDirectory())
                        ->rules(filament('filament-edit-profile')->getAvatarRules())
                        ->hidden(! filament('filament-edit-profile')->getShouldShowAvatarForm()),
                    Forms\Components\TextInput::make('name')
                        ->label(__('filament-edit-profile::default.name'))
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label(__('filament-edit-profile::default.email'))
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),
        ];
    }
}

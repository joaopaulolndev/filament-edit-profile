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

<?php

namespace Joaopaulolndev\FilamentEditProfile\Forms;

use Filament\Forms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditPasswordForm
{
    public static function get(): array
    {
        return [
            Forms\Components\Section::make(__('filament-edit-profile::default.update_password'))
                ->aside()
                ->description(__('filament-edit-profile::default.ensure_your_password'))
                ->schema([
                    Forms\Components\TextInput::make('Current password')
                        ->label(__('filament-edit-profile::default.current_password'))
                        ->password()
                        ->required()
                        ->currentPassword()
                        ->revealable(),
                    Forms\Components\TextInput::make('password')
                        ->label(__('filament-edit-profile::default.new_password'))
                        ->password()
                        ->required()
                        ->rule(Password::default())
                        ->autocomplete('new-password')
                        ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                        ->live(debounce: 500)
                        ->same('passwordConfirmation')
                        ->revealable(),
                    Forms\Components\TextInput::make('passwordConfirmation')
                        ->label(__('filament-edit-profile::default.confirm_password'))
                        ->password()
                        ->required()
                        ->dehydrated(false)
                        ->revealable(),
                ]),
        ];
    }
}

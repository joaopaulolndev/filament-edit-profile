<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeleteAccountForm extends BaseProfileForm
{
    protected string $view = 'filament-edit-profile::livewire.delete-account-form';

    protected static int $sort = 70;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament-edit-profile::default.delete_account'))
                    ->description(__('filament-edit-profile::default.delete_account_description'))
                    ->aside()
                    ->schema([
                        ViewField::make('deleteAccount')
                            ->label(__('Delete Account'))
                            ->hiddenLabel()
                            ->view('filament-edit-profile::forms.components.delete-account-description'),
                        Actions::make([
                            Action::make('deleteAccount')
                                ->label(__('filament-edit-profile::default.delete_account'))
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading(__('filament-edit-profile::default.delete_account'))
                                ->modalDescription(__('filament-edit-profile::default.are_you_sure'))
                                ->modalSubmitActionLabel(__('filament-edit-profile::default.yes_delete_it'))
                                ->schema([
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label(__('filament-edit-profile::default.password'))
                                        ->required(),
                                ])
                                ->action(function (array $data) {

                                    if (! Hash::check($data['password'], Auth::user()->password)) {
                                        $this->sendErrorDeleteAccount(__('filament-edit-profile::default.incorrect_password'));

                                        return;
                                    }

                                    auth()->user()?->delete();
                                }),
                        ]),
                    ]),
            ]);
    }

    public function sendErrorDeleteAccount(string $message): void
    {
        Notification::make()
            ->danger()
            ->title($message)
            ->send();
    }
}

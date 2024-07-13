<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeleteAccountForm extends BaseProfileForm
{
    protected string $view = 'filament-edit-profile::livewire.delete-account-form';

    protected static int $sort = 60;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament-edit-profile::default.delete_account'))
                    ->description(__('filament-edit-profile::default.delete_account_description'))
                    ->aside()
                    ->schema([
                        Forms\Components\ViewField::make('deleteAccount')
                            ->label(__('Delete Account'))
                            ->hiddenLabel()
                            ->view('filament-edit-profile::forms.components.delete-account-description'),
                        Actions::make([
                            Actions\Action::make('deleteAccount')
                                ->label(__('filament-edit-profile::default.delete_account'))
                                ->icon('heroicon-m-trash')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading(__('filament-edit-profile::default.delete_account'))
                                ->modalDescription(__('filament-edit-profile::default.are_you_sure'))
                                ->modalSubmitActionLabel(__('filament-edit-profile::default.yes_delete_it'))
                                ->form([
                                    Forms\Components\TextInput::make('password')
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

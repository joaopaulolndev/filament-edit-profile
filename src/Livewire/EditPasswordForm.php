<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;

class EditPasswordForm extends BaseProfileForm
{
    use HasUser;

    protected string $view = 'filament-edit-profile::livewire.edit-password-form';

    public ?array $data = [];

    protected static int $sort = 20;

    public function mount(): void
    {
        $this->user = $this->getUser();

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament-edit-profile::default.update_password'))
                    ->aside()
                    ->description(__('filament-edit-profile::default.ensure_your_password'))
                    ->schema([
                        TextInput::make('Current password')
                            ->label(__('filament-edit-profile::default.current_password'))
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),
                        TextInput::make('password')
                            ->label(__('filament-edit-profile::default.new_password'))
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation')
                            ->revealable(),
                        TextInput::make('passwordConfirmation')
                            ->label(__('filament-edit-profile::default.confirm_password'))
                            ->password()
                            ->required()
                            ->dehydrated(false)
                            ->revealable(),
                    ]),
            ])
            ->model($this->getUser())
            ->statePath('data');
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->form->getState();

            $newData = [
                'password' => $data['password'],
            ];

            $this->user->update($newData);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->form->fill();

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();
    }
}

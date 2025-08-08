<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;

class EditProfileForm extends BaseProfileForm
{
    use HasUser;

    protected string $view = 'filament-edit-profile::livewire.edit-profile-form';

    public ?array $data = [];

    public $userClass;

    protected static int $sort = 10;

    public function mount(): void
    {
        $this->user = $this->getUser();

        $this->userClass = get_class($this->user);

        $fields = ['name', 'email'];

        if (filament('filament-edit-profile')->getShouldShowAvatarForm()) {
            $fields[] = config('filament-edit-profile.avatar_column', 'avatar_url');
        }

        $this->form->fill($this->user->only($fields));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament-edit-profile::default.profile_information'))
                    ->aside()
                    ->description(__('filament-edit-profile::default.profile_information_description'))
                    ->schema([
                        FileUpload::make(config('filament-edit-profile.avatar_column', 'avatar_url'))
                            ->label(__('filament-edit-profile::default.avatar'))
                            ->avatar()
                            ->imageEditor()
                            ->disk(config('filament-edit-profile.disk', 'public'))
                            ->visibility(config('filament-edit-profile.visibility', 'public'))
                            ->directory(filament('filament-edit-profile')->getAvatarDirectory())
                            ->rules(filament('filament-edit-profile')->getAvatarRules())
                            ->hidden(! filament('filament-edit-profile')->getShouldShowAvatarForm()),
                        TextInput::make('name')
                            ->label(__('filament-edit-profile::default.name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('filament-edit-profile::default.email'))
                            ->email()
                            ->required()
                            ->hidden(! filament('filament-edit-profile')->getShouldShowEmailForm())
                            ->unique($this->userClass, ignorable: $this->user),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->form->getState();

            $this->user->update($data);

            $this->dispatch('refresh-topbar');
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();
    }
}

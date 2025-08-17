<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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

        if (filament('filament-edit-profile')->getShouldShowLocaleForm()) {
            $fields[] = config('filament-edit-profile.locale_column', 'locale');
        }

        if (filament('filament-edit-profile')->getShouldShowThemeColorForm()) {
            $fields[] = config('filament-edit-profile.theme_color_column', 'theme_color');
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
                        Select::make('locale')
                            ->label(__('filament-edit-profile::default.locale'))
                            ->options(filament('filament-edit-profile')->getOptionsLocaleForm())
                            ->required()
                            ->hidden(! filament('filament-edit-profile')->getShouldShowLocaleForm()),
                        ColorPicker::make('theme_color')
                            ->label(__('filament-edit-profile::default.theme_color'))
                            ->required()
                            ->hidden(! filament('filament-edit-profile')->getShouldShowThemeColorForm()),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateProfile(): void
    {
        $locale = null;
        $theme_color = null;
        if (filament('filament-edit-profile')->getShouldShowLocaleForm()) {
            $locale = $this->user->getAttributeValue('locale');
        }
        if (filament('filament-edit-profile')->getShouldShowThemeColorForm()) {
            $theme_color = $this->user->getAttributeValue('theme_color');
        }

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

        if (filament('filament-edit-profile')->getShouldShowLocaleForm()) {
            if ($locale !== $this->user->getAttributeValue('locale')) {
                redirect(request()->header('referer'));

                return;
            }
        }
        if (filament('filament-edit-profile')->getShouldShowThemeColorForm()) {
            if ($theme_color !== $this->user->getAttributeValue('theme_color')) {
                redirect(request()->header('referer'));
            }
        }
    }
}

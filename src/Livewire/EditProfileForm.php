<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;


use Joaopaulolndev\FilamentEditProfile\Notifications\ChangeEmailConfirmation;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class EditProfileForm extends BaseProfileForm
{
    use HasUser;

    protected string $view = 'filament-edit-profile::livewire.edit-profile-form';

    public ?array $data = [];

    public $userClass;

    public $avatar;

    protected static int $sort = 10;

    public bool $shouldEditEmail = true;

    public bool $shouldConfirmEmail = true;

    public function mount(): void
    {
        $this->shouldEditEmail = filament('filament-edit-profile')->shouldEditEmail;
        $this->shouldConfirmEmail = filament('filament-edit-profile')->shouldConfirmEmail;

        if(!$this->shouldEditEmail){
            $this->shouldConfirmEmail = false;
        }

        $this->user = $this->getUser();
        $this->userClass = get_class($this->user);

        $fields = [
            'name',
            'email',
        ];

        $this->form->fill($this->user->only($fields));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                /*                 Section::make(__('filament-edit-profile::default.profile_information'))
                    ->description(__('filament-edit-profile::default.profile_information_description'))
                    ->aside()
                    ->schema([ */
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->model($this->user)
                    ->label(__('filament-edit-profile::default.avatar'))
                    ->collection('avatar')
                    ->avatar()
                    ->imageEditor()
                    ->disk(config('filament-edit-profile.disk', 'public'))
                    ->visibility(config('filament-edit-profile.visibility', 'public'))
                    ->rules(filament('filament-edit-profile')->getAvatarRules())
                    ->hidden(! filament('filament-edit-profile')->getShouldShowAvatarForm()),
                TextInput::make('name')
                    ->label(__('filament-edit-profile::default.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('filament-edit-profile::default.email'))
                    ->email()
                    ->disabled(!$this->shouldEditEmail)
                    ->required($this->shouldEditEmail)
                    ->unique($this->userClass, ignorable: $this->user),
            ])
            /*  ,
            ]) */
            ->statePath('data');
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->form->getState();

            if (!$this->shouldConfirmEmail) {
                $this->user->update($data);
            } else {
                // Save the name change immediately
                $this->user->name = $data['name'];
                $this->user->save();

                if($this->user->email != $data['email']){
                    FacadesNotification::route('mail', $data['email'])
                        ->notify(new ChangeEmailConfirmation($data['email'], $this->user->id));
                    Notification::make()
                        ->success()
                        ->title(__('filament-edit-profile::default.email_verification_sent'))
                        ->body(__('filament-edit-profile::default.email_verification_sent_message'))
                        ->send();
                }
                else{
                    Notification::make()
                    ->success()
                    ->title(__('filament-edit-profile::default.saved_successfully'))
                    ->send();
                }
                return;
            }
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();
    }
}

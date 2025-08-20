<?php

namespace NoopStudios\FilamentEditProfile\Livewire;

use Filament\Auth\Notifications\NoticeOfEmailChangeRequest;
use Filament\Auth\Notifications\VerifyEmailChange;
use Filament\Facades\Filament;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use NoopStudios\FilamentEditProfile\Concerns\HasUser;
use League\Uri\Components\Query;

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

        FilamentNotification::make()
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

    private function sendEmailChangeVerification(Authenticatable & Model $user, string $newEmail): void
    {
        if ($user->getAttributeValue('email') === $newEmail) {
            return;
        }

        $notification = app(VerifyEmailChange::class);
        $notification->url = Filament::getVerifyEmailChangeUrl($user, $newEmail);

        $verificationSignature = Query::new($notification->url)->get('signature');

        cache()->put($verificationSignature, true, ttl: now()->addHour());

        $user->notify(app(NoticeOfEmailChangeRequest::class, [/** @phpstan-ignore-line */
            'blockVerificationUrl' => Filament::getBlockEmailChangeVerificationUrl($user, $newEmail, $verificationSignature),
            'newEmail' => $newEmail,
        ]));

        Notification::route('mail', $newEmail)
            ->notify($notification);

        $this->getEmailChangeVerificationSentNotification($newEmail)?->send();

        $this->data['email'] = $user->getAttributeValue('email');
    }

    private function getEmailChangeVerificationSentNotification(string $newEmail): ?FilamentNotification
    {
        return FilamentNotification::make()
            ->success()
            ->title(__('filament-panels::auth/pages/edit-profile.notifications.email_change_verification_sent.title', ['email' => $newEmail]))
            ->body(__('filament-panels::auth/pages/edit-profile.notifications.email_change_verification_sent.body', ['email' => $newEmail]));
    }
}

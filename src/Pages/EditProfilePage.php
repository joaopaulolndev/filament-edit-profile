<?php

namespace Joaopaulolndev\FilamentEditProfile\Pages;

use App\Filament\App\Pages\Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Joaopaulolndev\FilamentEditProfile\Forms\CustomFieldsForm;
use Joaopaulolndev\FilamentEditProfile\Forms\DeleteAccountForm;
use Joaopaulolndev\FilamentEditProfile\Forms\EditPasswordForm;
use Joaopaulolndev\FilamentEditProfile\Forms\EditProfileForm;

class EditProfilePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament-edit-profile::filament.pages.edit-profile-page';

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getShouldRegisterNavigation();
    }

    public static function getNavigationSort(): ?int
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getSort();
    }

    public static function getNavigationIcon(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getIcon();
    }

    public static function getNavigationGroup(): ?string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationGroup();
    }

    public function getTitle(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getTitle() ?? __('filament-edit-profile::default.title');
    }

    public static function getNavigationLabel(): string
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationLabel() ?? __('filament-edit-profile::default.title');
    }

    public static function canAccess(): bool
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getCanAccess();
    }

    public static function shouldShowDeleteAccountForm()
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getShouldShowDeleteAccountForm();
    }

    public static function shouldShowSanctumTokens()
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getshouldShowSanctumTokens();
    }

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public ?array $customFieldsData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
            'editProfileForm',
            'editPasswordForm',
            'deleteAccountForm',
            'customFieldsForm',
        ];
    }

    public function editProfileForm(Form $form): Form
    {
        return $form
            ->schema(EditProfileForm::get())
            ->model($this->getUser())
            ->statePath('profileData');
    }

    public function editPasswordForm(Form $form): Form
    {
        return $form
            ->schema(EditPasswordForm::get())
            ->model($this->getUser())
            ->statePath('passwordData');
    }

    public function deleteAccountForm(Form $form): Form
    {
        return $form
            ->schema(DeleteAccountForm::get())
            ->model($this->getUser())
            ->statePath('deleteAccountData');
    }

    public function customFieldsForm(Form $form): Form
    {
        if (config('filament-edit-profile.show_custom_fields') && ! empty(config('filament-edit-profile.custom_fields'))) {
            return $form
                ->schema(CustomFieldsForm::get(config('filament-edit-profile.custom_fields')))
                ->model($this->getUser())
                ->statePath('customFieldsData');
        }

        return $form
            ->schema([])
            ->model($this->getUser())
            ->statePath('customFieldsData');
    }

    protected function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception(__('filament-edit-profile::default.user_load_error'));
        }

        return $user;
    }

    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->editProfileForm->fill($data);
        $this->editPasswordForm->fill();

        if (config('filament-edit-profile.show_custom_fields') && ! empty(config('filament-edit-profile.custom_fields'))) {
            $this->customFieldsForm->fill($data['custom_fields'] ?? []);
        }
    }

    protected function getUpdateProfileFormActions(): array
    {
        return [
            Action::make('updateProfileAction')
                ->label(__('filament-edit-profile::default.save'))
                ->submit('editProfileForm'),
        ];
    }

    protected function getUpdatePasswordFormActions(): array
    {
        return [
            Action::make('updatePasswordAction')
                ->label(__('filament-edit-profile::default.save'))
                ->submit('editPasswordForm'),
        ];
    }

    protected function getUpdateCustomFieldsFormActions(): array
    {
        return [
            Action::make('updateCustomFieldsAction')
                ->label(__('filament-edit-profile::default.save'))
                ->submit('editCustomFieldsForm'),
        ];
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->editProfileForm->getState();

            $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->editPasswordForm->getState();

            $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->editPasswordForm->fill();

        $this->sendSuccessNotification();
    }

    public function updateCustomFields(): void
    {
        try {
            $data = $this->customFieldsForm->getState();
            $data['custom_fields'] = $data ?? [];
            $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }

    private function sendSuccessNotification(): void
    {
        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();

        redirect(request()?->header('Referer'));
    }
}

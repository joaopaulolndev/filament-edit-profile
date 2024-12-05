<?php

namespace Joaopaulolndev\FilamentEditProfile\Pages;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Facades\Session;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Laravel\Fortify\Events\RecoveryCodeReplaced;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class TwoFactorPage extends SimplePage implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-edit-profile::filament.pages.two-factor-page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Checkbox::make('use_recovery_code')
                    ->inline()
                    ->live()
                    ->label(__('filament-edit-profile::default.two_factor.use_recovery_code'))
                    ->default(false)
                    ->afterStateUpdated(function (Set $set): void {
                        $set('code', '');
                        $set('recovery_code', '');
                    }),
                TextInput::make('code')
                    ->label(__('filament-edit-profile::default.two_factor.code'))
                    ->required()
                    ->hidden(fn (Get $get) => $get('use_recovery_code'))
                    ->disabled(fn (Get $get) => $get('use_recovery_code')),
                TextInput::make('recovery_code')
                    ->label(__('filament-edit-profile::default.two_factor.recovery_code'))
                    ->required(fn (Get $get) => $get('use_recovery_code'))
                    ->hidden(fn (Get $get) => ! $get('use_recovery_code'))
                    ->disabled(fn (Get $get) => ! $get('use_recovery_code')),
            ])->statePath('data');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/admin/login');
    }

    public function save()
    {
        $data = $this->form->getState();

        if ($data['use_recovery_code'] && $this->validRecoveryCode($data) == true) {
            $user = auth()->user();
            $code = $data['recovery_code'];
            $user->replaceRecoveryCode($code);
            event(new RecoveryCodeReplaced($user, $code));
            Session::put('two_factor_approved', true);
            Notification::make()
                ->success()
                ->title(__('filament-edit-profile::default.two_factor.notification_title_approved'))
                ->send();

            return redirect()->route('filament.admin.pages.dashboard');

        } elseif (! $data['use_recovery_code'] && $this->hasValidCode($data) == true) {
            Session::put('two_factor_approved', true);
            Notification::make()
                ->success()
                ->title(__('filament-edit-profile::default.two_factor.notification_title_approved'))
                ->send();

            return redirect()->route('filament.admin.pages.dashboard');

        } else {
            Notification::make()
                ->danger()
                ->title(__('filament-edit-profile::default.two_factor.code_incorrect'))
                ->send();

            return;
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-edit-profile::default.two_factor.button_confirm'))
                ->submit('save'),
        ];
    }

    protected function getLogoutActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-edit-profile::default.two_factor.you_can_logout'))
                ->icon('heroicon-o-arrow-left-end-on-rectangle')
                ->submit('logout')
                ->color('danger')
                ->link(),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    private function validRecoveryCode($data)
    {
        if (! isset($data['recovery_code'])) {
            return false;
        }

        $recovery_code = $data['recovery_code'];
        $collection = auth()->user()->recoveryCodes();

        return collect($collection)
            ->first(function ($code) use ($recovery_code) {
                return hash_equals($code, $recovery_code) ? $code : false;
            }
            );
    }

    private function hasValidCode($data)
    {
        if (! isset($data['code'])) {
            return false;
        }

        $code = $data['code'];

        return $code && app(TwoFactorAuthenticationProvider::class)->verify(
            decrypt(auth()->user()->two_factor_secret), $code);
    }
}

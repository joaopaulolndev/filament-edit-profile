<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Closure;
use Filament\Forms;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;

class TwoFactorForm extends Component implements HasForms
{
    use HasSort;
    use InteractsWithForms;

    protected static int $sort = 40;

    public ?array $data = [];

    public static function getEnabledTwoFactor(): array
    {
        if (Filament::auth()->user()->hasEnabledTwoFactorAuthentication()) {
            return [
                'qrCodeSvg' => Filament::auth()->user()->twoFactorQrCodeSvg(),
                'secretCode' => decrypt(Filament::auth()->user()->two_factor_secret),
                'recoveryCodes' => Filament::auth()->user()->recoveryCodes(),
            ];
        } else {
            return [];
        }

    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament-edit-profile::default.two_factor.heading'))
                    ->aside()
                    ->description(__('filament-edit-profile::default.two_factor.description'))
                    ->schema([
                        Forms\Components\ViewField::make('Two factor')
                            ->hiddenLabel()
                            ->hidden(! Filament::auth()->user()->hasEnabledTwoFactorAuthentication())
                            ->view('filament-edit-profile::forms.components.view-disabled-two-factor')
                            ->viewData(['data' => self::getEnabledTwoFactor()]),
                        Actions::make([
                            Actions\Action::make('disabledTwoFactor')
                                ->label(__('filament-edit-profile::default.two_factor.disabled.label'))
                                ->requiresConfirmation()
                                ->modalHeading(__('filament-edit-profile::default.two_factor.disabled.modal_heading'))
                                ->modalDescription(__('filament-edit-profile::default.two_factor.disabled.modal_description'))
                                ->modalSubmitActionLabel(__('filament-edit-profile::default.two_factor.disabled.modal_submit_action_label'))
                                ->form([
                                    Forms\Components\TextInput::make('password')
                                        ->label(__('filament-edit-profile::default.password'))
                                        ->password()
                                        ->revealable()
                                        ->required()
                                        ->rules([
                                            fn (): Closure => function (string $attribute, $value, Closure $fail): void {
                                                if (! Hash::check($value, Filament::auth()->user()->password)) {
                                                    $fail(__('filament-edit-profile::default.two_factor.password_incorrect', ['attribute' => __('filament-edit-profile::default.password')]));
                                                }
                                            },
                                        ]),
                                ])
                                ->action(function (array $data): void {
                                    self::disabledTwoFactor();
                                })
                                ->modalWidth('lg'),
                        ])->hidden(! Filament::auth()->user()->hasEnabledTwoFactorAuthentication()),
                        Forms\Components\ViewField::make('Two factor')
                            ->hiddenLabel()
                            ->hidden(Filament::auth()->user()->hasEnabledTwoFactorAuthentication())
                            ->view('filament-edit-profile::forms.components.view-enabled-two-factor'),
                        Actions::make([
                            Actions\Action::make('enabledTwoFactor')
                                ->label(__('filament-edit-profile::default.two_factor.enabled.label'))
                                ->requiresConfirmation()
                                ->modalHeading(__('filament-edit-profile::default.two_factor.enabled.modal_heading'))
                                ->modalDescription(__('filament-edit-profile::default.two_factor.enabled.modal_description'))
                                ->modalSubmitActionLabel(__('filament-edit-profile::default.two_factor.enabled.modal_submit_action_label'))
                                ->form([
                                    Forms\Components\TextInput::make('password')
                                        ->label(__('filament-edit-profile::default.password'))
                                        ->password()
                                        ->revealable()
                                        ->required()
                                        ->rules([
                                            fn (): Closure => function (string $attribute, $value, Closure $fail): void {
                                                if (! Hash::check($value, Filament::auth()->user()->password)) {
                                                    $fail(__('filament-edit-profile::default.two_factor.password_incorrect', ['attribute' => __('filament-edit-profile::default.password')]));
                                                }
                                            },
                                        ]),
                                ])
                                ->action(function (array $data): void {
                                    self::enabledTwoFactor();
                                })
                                ->modalWidth('lg'),
                        ])->hidden(Filament::auth()->user()->hasEnabledTwoFactorAuthentication()),
                    ]),
            ])
            ->statePath('data');
    }

    public function disabledTwoFactor(): void
    {
        $disable = app(DisableTwoFactorAuthentication::class);
        $disable(Filament::auth()->user());

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.two_factor.disabled.notification_title'))
            ->send();

    }

    public function enabledTwoFactor(): void
    {
        $enable = app(EnableTwoFactorAuthentication::class);
        $enable(Filament::auth()->user(), false);

        Session::put('two_factor_approved', true);

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.two_factor.enabled.notification_title'))
            ->send();
    }

    public function save(): void
    {
        $data = $this->form->getState();
    }

    public function render(): View
    {
        return view('filament-edit-profile::livewire.two-factor-form');
    }
}

<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class BrowserSessionsForm extends BaseProfileForm
{
    protected string $view = 'filament-edit-profile::livewire.browser-sessions-form';

    protected static int $sort = 50;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament-edit-profile::default.browser_section_title'))
                    ->description(__('filament-edit-profile::default.browser_section_description'))
                    ->aside()
                    ->schema([
                        Forms\Components\ViewField::make('browserSessions')
                            ->label(__(__('filament-edit-profile::default.browser_section_title')))
                            ->hiddenLabel()
                            ->view('filament-edit-profile::forms.components.browser-sessions')
                            ->viewData(['data' => self::getSessions()]),
                        Actions::make([
                            Actions\Action::make('deleteBrowserSessions')
                                ->label(__('filament-edit-profile::default.browser_sessions_log_out'))
                                ->requiresConfirmation()
                                ->modalHeading(__('filament-edit-profile::default.browser_sessions_log_out'))
                                ->modalDescription(__('filament-edit-profile::default.browser_sessions_confirm_pass'))
                                ->modalSubmitActionLabel(__('filament-edit-profile::default.browser_sessions_log_out'))
                                ->form([
                                    Forms\Components\TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label(__('filament-edit-profile::default.password'))
                                        ->required(),
                                ])
                                ->action(function (array $data) {
                                    self::logoutOtherBrowserSessions($data['password']);
                                })
                                ->modalWidth('2xl'),
                        ]),

                    ]),
            ]);
    }

    public static function getSessions(): array
    {
        if (config(key: 'session.driver') !== 'database') {
            return [];
        }

        return collect(
            value: DB::connection(config(key: 'session.connection'))->table(table: config(key: 'session.table', default: 'sessions'))
                ->where(column: 'user_id', operator: Auth::user()->getAuthIdentifier())
                ->latest(column: 'last_activity')
                ->get()
        )->map(callback: function ($session): object {
            $agent = self::createAgent($session);

            return (object) [
                'device' => [
                    'browser' => $agent->browser(),
                    'desktop' => $agent->isDesktop(),
                    'mobile' => $agent->isMobile(),
                    'tablet' => $agent->isTablet(),
                    'platform' => $agent->platform(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        })->toArray();
    }

    protected static function createAgent(mixed $session)
    {
        return tap(
            value: new Agent,
            callback: fn ($agent) => $agent->setUserAgent(userAgent: $session->user_agent)
        );
    }

    public static function logoutOtherBrowserSessions($password): void
    {
        if (! Hash::check($password, Auth::user()->password)) {
            Notification::make()
                ->danger()
                ->title(__('filament-edit-profile::default.incorrect_password'))
                ->send();

            return;
        }

        Auth::guard()->logoutOtherDevices($password);

        request()->session()->put([
            'password_hash_' . Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
        ]);

        self::deleteOtherSessionRecords();

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.browser_sessions_logout_success_notification'))
            ->send();
    }

    protected static function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }
}

<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;
use Laravel\Sanctum\Sanctum;

class SanctumTokens extends BaseProfileForm implements HasTable
{
    use HasUser;
    use InteractsWithTable;

    protected string $view = 'filament-edit-profile::livewire.sanctum-tokens';

    public ?string $plainTextToken;

    protected static int $sort = 40;

    public function mount()
    {
        $this->user = $this->getUser();
    }

    public function table(Table $table): Table
    {
        $auth = Filament::getCurrentPanel()->auth();

        return $table
            ->query(app(Sanctum::$personalAccessTokenModel)->where([
                ['tokenable_id', '=', $auth->id()],
                ['tokenable_type', '=', get_class($auth->user())],
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament-edit-profile::default.token_name')),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label(__('filament-edit-profile::default.token_created_at'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->color(fn ($record) => now()->gt($record->expires_at) ? 'danger' : null)
                    ->date()
                    ->label(__('filament-edit-profile::default.token_expires_at'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('filament-edit-profile::default.token_action_label'))
                    ->modalWidth('md')
                    ->form([
                        TextInput::make('token_name')
                            ->label(__('filament-edit-profile::default.token_name'))
                            ->required(),
                        CheckboxList::make('abilities')
                            ->label(__('filament-edit-profile::default.token_abilities'))
                            ->options(filament('filament-edit-profile')->getSanctumPermissions())
                            ->columns(2)
                            ->required(),
                        DatePicker::make('expires_at')
                            ->label(__('filament-edit-profile::default.token_expires_at')),
                    ])
                    ->action(function ($data) {
                        $this->plainTextToken = $this->user->createToken(
                            $data['token_name'],
                            array_values($data['abilities']),
                            $data['expires_at'] ? Carbon::createFromFormat('Y-m-d', $data['expires_at']) : null
                        )->plainTextToken;

                        $this->replaceMountedAction('showToken', [
                            'token' => $this->plainTextToken,
                        ]);

                        Notification::make()
                            ->success()
                            ->title(__('filament-edit-profile::default.token_create_notification'))
                            ->send();
                    })
                    ->modalHeading(__('filament-edit-profile::default.token_modal_heading')),
            ])
            ->emptyStateHeading(__('filament-edit-profile::default.token_empty_state_heading'))
            ->emptyStateDescription(__('filament-edit-profile::default.token_empty_state_description'));
    }

    public function showTokenAction(): Action
    {
        return Action::make('token')
            ->fillForm(fn (array $arguments) => [
                'token' => $arguments['token'],
            ])
            ->form([
                TextInput::make('token')
                    ->helperText(__('filament-edit-profile::default.token_helper_text')),
            ])
            ->modalHeading(__('filament-edit-profile::default.token_modal_heading_2'))
            ->modalIcon('heroicon-o-key')
            ->modalAlignment(Alignment::Center)
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->closeModalByClickingAway(false);
    }
}

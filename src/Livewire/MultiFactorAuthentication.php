<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Auth\MultiFactor\Contracts\MultiFactorAuthenticationProvider;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;

class MultiFactorAuthentication extends BaseProfileForm
{
    use HasUser;

    protected static int $sort = 60;

    protected string $view = 'filament-edit-profile::livewire.multi-factor-authentication';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('filament-edit-profile::default.mfa_section_title'))
                    ->description(__('filament-edit-profile::default.mfa_section_description'))
                    ->aside()
                    ->schema([
                        $this->getMultiFactorAuthenticationContentComponent(),
                    ]),
            ]);
    }

    public function getMultiFactorAuthenticationContentComponent(): Component
    {
        $user = Filament::auth()->user();

        return Section::make()
            ->compact()
            ->divided()
            ->secondary()
            ->schema(collect(Filament::getMultiFactorAuthenticationProviders())
                ->sort(fn (MultiFactorAuthenticationProvider $multiFactorAuthenticationProvider): int => $multiFactorAuthenticationProvider->isEnabled($user) ? 0 : 1)
                ->map(fn (MultiFactorAuthenticationProvider $multiFactorAuthenticationProvider): Component => Group::make($multiFactorAuthenticationProvider->getManagementSchemaComponents())
                    ->statePath($multiFactorAuthenticationProvider->getId()))
                ->all());
    }
}

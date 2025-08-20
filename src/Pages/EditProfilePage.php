<?php

namespace NoopStudios\FilamentEditProfile\Pages;

use Filament\Facades\Filament;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Schema;

class EditProfilePage extends Page
{
    use InteractsWithForms;
    
    protected string $view = 'filament-edit-profile::filament.pages.edit-profile-page';

    protected static ?string $slug = 'edit-profile';

    public ?string $activeTab = 'profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->buildTabs(),
            ])
            ->statePath('data');
    }

    /**
     * Build the tabs component with all the appropriate forms
     */
    protected function buildTabs(): Tabs
    {
        $plugin = Filament::getCurrentPanel()?->getPlugin('filament-edit-profile');
        $tabsConfig = [];

        $components = $plugin->getRegisteredCustomProfileComponents();

        foreach($components as $component){
          
            if($component == 'NoopStudios\FilamentEditProfile\Livewire\EditProfileForm'){
                $tabsConfig[] = Tab::make(__('filament-edit-profile::default.profile_information'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        View::make('filament-edit-profile::livewire-component-view')
                            ->view('filament-edit-profile::livewire-component-view')
                            ->viewData(['component' => 'edit_profile_form']),
                    ]);
            }

            if($component == 'NoopStudios\FilamentEditProfile\Livewire\EditPasswordForm'){
                $tabsConfig[] = Tab::make(__('filament-edit-profile::default.password'))
                    ->icon('heroicon-o-key')
                    ->schema([
                        View::make('edit_password_form')
                            ->view('filament-edit-profile::livewire-component-view')
                            ->viewData(['component' => 'edit_password_form']),
                    ]);
            }

            if($component == 'NoopStudios\FilamentEditProfile\Livewire\DeleteAccountForm'){
                $tabsConfig[] = Tab::make(__('filament-edit-profile::default.delete_account'))
                    ->icon('heroicon-o-trash')
                    ->schema([
                        View::make('delete_account_form')
                            ->view('filament-edit-profile::livewire-component-view')
                            ->viewData(['component' => 'delete_account_form']),
                    ]);
            }

            if($component == 'NoopStudios\FilamentEditProfile\Livewire\SanctumTokens'){
                $tabsConfig[] = Tab::make(__('filament-edit-profile::default.api_tokens_title'))
                    ->icon('heroicon-o-key')
                    ->schema([
                        View::make('sanctum_tokens')
                            ->view('filament-edit-profile::livewire-component-view')
                            ->viewData(['component' => 'sanctum_tokens']),
                    ]);
            }   

            if($component == 'NoopStudios\FilamentEditProfile\Livewire\BrowserSessionsForm'){
                $tabsConfig[] = Tab::make(__('filament-edit-profile::default.browser_section_title'))
                    ->icon('heroicon-o-computer-desktop')
                    ->schema([
                        View::make('browser_sessions_form')
                            ->view('filament-edit-profile::livewire-component-view')
                            ->viewData(['component' => 'browser_sessions_form']),
                    ]);
            }

            if($component == 'NoopStudios\FilamentEditProfile\Livewire\CustomFieldsForm'){
                $tabsConfig[] = Tab::make(__('filament-edit-profile::default.custom_fields'))
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        View::make('custom_fields_form')
                            ->view('filament-edit-profile::livewire-component-view')
                            ->viewData(['component' => 'custom_fields_form']),
                    ]);
            }

        }
        
        return Tabs::make('profile_tabs')
            ->tabs($tabsConfig);
    }

    /* public static function getSlug(): ?string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        $slug = $plugin?->getSlug();

        return $slug ? $slug : self::$slug;
    }
 */
    public static function getSlug(?Panel $panel = null): string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        $slug = $plugin?->getSlug();

        return $slug ? $slug : self::$slug;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getShouldRegisterNavigation();
    }

    public static function getNavigationSort(): ?int
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getSort();
    }

    public static function getNavigationIcon(): ?string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getIcon();
    }

    public static function getNavigationGroup(): ?string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationGroup();
    }

    public function getTitle(): string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getTitle() ?? __('filament-edit-profile::default.title');
    }

    public static function getNavigationLabel(): string
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getNavigationLabel() ?? __('filament-edit-profile::default.title');
    }

    public static function canAccess(): bool
    {
        $plugin = Filament::getCurrentOrDefaultPanel()?->getPlugin('filament-edit-profile');

        return $plugin->getCanAccess();
    }

    public function getRegisteredCustomProfileComponents(): array
    {
        return filament('filament-edit-profile')->getRegisteredCustomProfileComponents();
    }
}

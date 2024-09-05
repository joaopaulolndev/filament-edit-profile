<?php

namespace Joaopaulolndev\FilamentEditProfile;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Joaopaulolndev\FilamentEditProfile\Livewire\BrowserSessionsForm;
use Joaopaulolndev\FilamentEditProfile\Livewire\CustomFieldsForm;
use Joaopaulolndev\FilamentEditProfile\Livewire\DeleteAccountForm;
use Joaopaulolndev\FilamentEditProfile\Livewire\EditPasswordForm;
use Joaopaulolndev\FilamentEditProfile\Livewire\EditProfileForm;
use Joaopaulolndev\FilamentEditProfile\Livewire\SanctumTokens;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Livewire\Livewire;

class FilamentEditProfilePlugin implements Plugin
{
    use EvaluatesClosures;

    public Closure | bool $access = true;

    public Closure | bool $shouldRegisterNavigation = true;

    public Closure | int $sort = 90;

    public Closure | string $icon = '';

    public Closure | string $navigationGroup = '';

    public Closure | string $title = '';

    public Closure | string $slug = '';

    public Closure | string $navigationLabel = '';

    public bool $shouldShowEditProfileForm = true;

    public bool $shouldShowEditPasswordForm = true;

    public Closure | bool $shouldShowDeleteAccountForm = true;

    public Closure | bool $shouldShowBrowserSessionsForm = true;

    protected Closure | bool $sanctumTokens = false;

    protected array $sanctumPermissions = ['create', 'view', 'update', 'delete'];

    protected Closure | bool $shouldShowAvatarForm = false;

    protected string $avatarDirectory = 'avatars';

    protected array | string $avatarRules = ['max:1024'];

    protected array $registeredCustomProfileComponents = [];

    public function getId(): string
    {
        return 'filament-edit-profile';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages($this->preparePages());
    }

    protected function preparePages(): array
    {

        return [
            EditProfilePage::class,
        ];
    }

    public function boot(Panel $panel): void
    {
        $this->registerLivewireComponents();
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function setTitle(Closure | string $value = ''): static
    {
        $this->title = $value;

        return $this;
    }

    public function getTitle(): ?string
    {
        return ! empty($this->title) ? $this->evaluate($this->title) : null;
    }

    public function slug(Closure | string $value = 'edit-profile'): static
    {
        $this->slug = $value;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->evaluate($this->slug);
    }

    public function setNavigationLabel(Closure | string $value = ''): static
    {
        $this->navigationLabel = $value;

        return $this;
    }

    public function getNavigationLabel(): ?string
    {
        return ! empty($this->navigationLabel) ? $this->evaluate($this->navigationLabel) : null;
    }

    public function setNavigationGroup(Closure | string $value = ''): static
    {
        $this->navigationGroup = $value;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return ! empty($this->navigationGroup) ? $this->evaluate($this->navigationGroup) : null;
    }

    public function setIcon(Closure | string $value = ''): static
    {
        $this->icon = $value;

        return $this;
    }

    public function getIcon(): ?string
    {
        return ! empty($this->icon) ? $this->evaluate($this->icon) : null;
    }

    public function setSort(Closure | int $value = 100): static
    {
        $this->sort = $value;

        return $this;
    }

    public function getSort(): int
    {
        return $this->evaluate($this->sort);
    }

    public function canAccess(Closure | bool $value = true): static
    {
        $this->access = $value;

        return $this;
    }

    public function getCanAccess(): bool
    {
        return $this->evaluate($this->access);
    }

    public function shouldRegisterNavigation(Closure | bool $value = true): static
    {
        $this->shouldRegisterNavigation = $value;

        return $this;
    }

    public function getShouldRegisterNavigation(): bool
    {
        return $this->evaluate($this->shouldRegisterNavigation);
    }

    public function shouldShowEditProfileForm(bool $value = true): static
    {
        $this->shouldShowEditProfileForm = $value;

        return $this;
    }

    public function getShouldShowEditProfileForm(): bool
    {
        return $this->evaluate($this->shouldShowEditProfileForm);
    }

    public function shouldShowEditPasswordForm(bool $value = true): static
    {
        $this->shouldShowEditPasswordForm = $value;

        return $this;
    }

    public function getShouldShowEditePasswordForm(): bool
    {
        return $this->evaluate($this->shouldShowEditPasswordForm);
    }

    public function shouldShowDeleteAccountForm(Closure | bool $value = true): static
    {
        $this->shouldShowDeleteAccountForm = $value;

        return $this;
    }

    public function getShouldShowDeleteAccountForm(): bool
    {
        return $this->evaluate($this->shouldShowDeleteAccountForm);
    }

    public function shouldShowBrowserSessionsForm(Closure | bool $value = true): static
    {
        $this->shouldShowBrowserSessionsForm = $value;

        if (config('session.driver') !== 'database') {
            $this->shouldShowBrowserSessionsForm = false;
        }

        return $this;
    }

    public function getShouldShowBrowserSessionsForm(): bool
    {
        if (config('session.driver') !== 'database') {
            $this->shouldShowBrowserSessionsForm = false;
        }

        return $this->evaluate($this->shouldShowBrowserSessionsForm);
    }

    public function getShouldShowSanctumTokens(): bool
    {
        return $this->evaluate($this->sanctumTokens);
    }

    public function shouldShowSanctumTokens(Closure | bool $condition = true, ?array $permissions = null)
    {
        $this->sanctumTokens = $condition;

        if (! is_null($permissions)) {
            $this->sanctumPermissions = $permissions;
        }

        return $this;
    }

    public function getSanctumPermissions(): array
    {
        return collect($this->sanctumPermissions)->mapWithKeys(function ($item, $key) {
            $key = is_string($key) ? $key : strtolower($item);

            return [$key => $item];
        })->toArray();
    }

    public function shouldShowAvatarForm(Closure | bool $value = true, ?string $directory = null, string | array | null $rules = null): static
    {
        $this->shouldShowAvatarForm = $value;

        if (! is_null($directory)) {
            $this->avatarDirectory = $directory;
        }

        if (! is_null($rules)) {
            $this->avatarRules = $rules;
        }

        return $this;
    }

    public function getShouldShowAvatarForm(): bool
    {
        return $this->evaluate($this->shouldShowAvatarForm);
    }

    public function getAvatarDirectory(): string
    {
        return $this->avatarDirectory;
    }

    public function getAvatarRules(): array | string
    {
        return $this->avatarRules;
    }

    private function registerLivewireComponents(): void
    {
        $components = collect();

        if ($this->shouldShowEditProfileForm) {
            $components->put('edit_profile_form', EditProfileForm::class);
        }

        if ($this->shouldShowEditPasswordForm) {
            $components->put('edit_password_form', EditPasswordForm::class);
        }

        if ($this->getShouldShowDeleteAccountForm()) {
            $components->put('delete_account_form', DeleteAccountForm::class);
        }

        if ($this->getShouldShowSanctumTokens()) {
            $components->put('sanctum_tokens', SanctumTokens::class);
        }

        if ($this->getShouldShowBrowserSessionsForm()) {
            $components->put('browser_sessions_form', BrowserSessionsForm::class);
        }

        if (config('filament-edit-profile.show_custom_fields') && ! empty(config('filament-edit-profile.custom_fields'))) {
            $components->put('custom_fields_form', CustomFieldsForm::class);
        }

        $components->each(function ($class, $name) {
            Livewire::component($name, $class);
        });

        $this->customProfileComponents($components->toArray());
    }

    public function customProfileComponents(array $components): self
    {
        $this->registeredCustomProfileComponents = array_merge(
            $this->registeredCustomProfileComponents,
            $components
        );

        return $this;
    }

    public function getRegisteredCustomProfileComponents(): array
    {
        return collect($this->registeredCustomProfileComponents)
            ->sortBy(fn (string $component) => $component::getSort())
            ->all();
    }
}

<form wire:submit="updateProfile" class="fi-sc-form">
    {{ $this->form }}

    <div class="fi-ac fi-align-end">
        <x-filament::button type="submit">
            {{ __('filament-edit-profile::default.save') }}
        </x-filament::button>
    </div>
</form>

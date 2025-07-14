<form wire:submit="updatePassword">
    {{ $this->form }}

    <div class="fi-form-actions mt-4">
        <div class="flex flex-row-reverse flex-wrap items-center gap-3 fi-ac">
            <x-filament::button type="submit">
                {{ __('filament-edit-profile::default.save') }}
            </x-filament::button>
        </div>
    </div>
</form>

<x-filament-panels::page>
    <x-filament-panels::form wire:submit="updateProfile">
        {{ $this->editProfileForm }}

        <x-filament-panels::form.actions
            alignment="right"
            :actions="$this->getUpdateProfileFormActions()"
        />
    </x-filament-panels::form>

    <x-filament-panels::form wire:submit="updatePassword">
        {{ $this->editPasswordForm }}

        <x-filament-panels::form.actions
            alignment="right"
            :actions="$this->getUpdatePasswordFormActions()"
        />
    </x-filament-panels::form>

    @if(config('filament-edit-profile.show_custom_fields'))
        <x-filament-panels::form wire:submit="updateCustomFields">
            {{ $this->customFieldsForm }}

            <x-filament-panels::form.actions
                alignment="right"
                :actions="$this->getUpdateCustomFieldsFormActions()"
            />
        </x-filament-panels::form>
    @endif


    @if($this->shouldShowDeleteAccountForm())
        <x-filament-panels::form>
            {{ $this->deleteAccountForm }}
        </x-filament-panels::form>
    @endif
</x-filament-panels::page>

<!-- content -->
<div class="space-y-6">
    

  <x-filament-panels::form wire:submit="updateProfile"> 
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit">
                {{ __('filament-edit-profile::default.save') }}
            </x-filament::button>
        </div>
    </x-filament-panels::form>

    
</div>
<!-- content -->

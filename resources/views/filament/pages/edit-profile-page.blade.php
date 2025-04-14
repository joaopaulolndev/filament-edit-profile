<!-- content -->
<x-filament-panels::page>
    <x-filament-panels::form>
        {{ $this->form }}
    </x-filament-panels::form>
    
    <!-- Additional custom components -->
    @foreach ($this->getAdditionalComponents() as $component)
        <div class="mt-8">
            @livewire($component)
        </div>
    @endforeach
</x-filament-panels::page>
<!-- content -->
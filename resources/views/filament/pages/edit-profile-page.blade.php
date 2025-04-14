<x-filament-panels::page>
    <!-- Tab navigation -->
    <x-filament::tabs>
        @foreach ($this->getTabs() as $tabKey => $tabData)
            <x-filament::tabs.item
                :active="$activeTab === $tabKey"
                :icon="$tabData['icon'] ?? null"
                wire:click="$set('activeTab', '{{ $tabKey }}')"
            >
                {{ $tabData['label'] }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    <!-- Tab content -->
    <div class="mt-6">
        @if ($activeTab === 'profile')
            @livewire('edit_profile_form')
        @elseif ($activeTab === 'password')
            @livewire('edit_password_form')
        @elseif ($activeTab === 'delete-account')
            @livewire('delete_account_form')
        @elseif ($activeTab === 'api-tokens')
            @livewire('sanctum_tokens')
        @elseif ($activeTab === 'browser-sessions')
            @livewire('browser_sessions_form')
        @elseif ($activeTab === 'custom-fields')
            @livewire('custom_fields_form')
        @endif
    </div>
</x-filament-panels::page>

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div>
        <div class="">
            <div class="text-sm text-gray-600 fi-in-entry">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('filament-edit-profile::default.browser_sessions_content') }}
                </div>
                @if (count($data) > 0)
                    <div class="fi-in-entry">
                        @foreach ($data as $session)
                            <div class="fi-sc fi-inline fi-sc-has-gap">
                                <div>
                                    @if ($session->device['desktop'])
                                        <x-filament::icon
                                            icon="heroicon-o-computer-desktop"
                                            class="w-8 h-8 text-gray-500 dark:text-gray-400"
                                        />
                                    @else
                                        <x-filament::icon
                                            icon="heroicon-o-device-phone-mobile"
                                            class="w-8 h-8 text-gray-500 dark:text-gray-400"
                                        />
                                    @endif
                                </div>

                                <div class="ms-3">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $session->device['platform'] ? $session->device['platform'] : __('Unknown') }} - {{ $session->device['browser'] ? $session->device['browser'] : __('Unknown') }}
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-500">
                                            {{ $session->ip_address }},

                                            @if ($session->is_current_device)
                                                <span class="font-semibold text-primary-500">{{ __('filament-edit-profile::default.browser_sessions_device') }}</span>
                                            @else
                                            {{ __('filament-edit-profile::default.browser_sessions_last_active') }} {{ $session->last_active }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-dynamic-component>

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="mx-auto max-w-lg">
        <div class="text-sm text-gray-600 dark:text-gray-300">
            <p class="text-lg font-semibold dark:text-gray-200">{{ __('filament-edit-profile::default.two_factor.disabled.heading') }}</p>
            <p class="mt-2 dark:text-gray-400">
                {{ __('filament-edit-profile::default.two_factor.disabled.description') }}
            </p>
            <p class="mb-2 mt-2 dark:text-gray-400">
                {{ __('filament-edit-profile::default.two_factor.disabled.sub_description') }}
            </p>

            <div className="mt-4 flex justify-center">
                <div className="w-40 h-40">
                    {!! $data['qrCodeSvg'] !!}
                </div>
            </div>

            <div class="mt-4 flex justify-center">
                <span class="rounded-lg bg-gray-200 px-4 py-2 text-gray-800">
                    {{ __('filament-edit-profile::default.two_factor.disabled.setup_key') }} {{ $data['secretCode'] }}
                </span>
            </div>

            @if (count($data['recoveryCodes']) > 0)
                <div class="mt-4">
                    <p class="font-semibold dark:text-gray-400">
                        {{ __('filament-edit-profile::default.two_factor.disabled.recovery_code_description') }}
                    </p>
                    <div class="mt-2 rounded-lg bg-gray-100 p-4 dark:bg-gray-800">
                        @foreach ($data['recoveryCodes'] as $recoveryCode)
                            <div class="font-mono text-gray-900 dark:text-gray-400">{{ $recoveryCode }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-dynamic-component>

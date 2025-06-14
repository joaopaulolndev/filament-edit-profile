<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="mx-auto max-w-lg">
        <div class="text-sm text-gray-600 dark:text-gray-200">
            <p class="text-lg font-semibold dark:text-gray-200">{{ __('filament-edit-profile::default.two_factor.enabled.heading') }}</p>
            <p class="mt-4 dark:text-gray-400">
                {{ __('filament-edit-profile::default.two_factor.enabled.description') }}
            </p>
            <p class="mt-4 dark:text-gray-400">
                {{ __('filament-edit-profile::default.two_factor.enabled.sub_description') }}
            </p>
        </div>
    </div>
</x-dynamic-component>

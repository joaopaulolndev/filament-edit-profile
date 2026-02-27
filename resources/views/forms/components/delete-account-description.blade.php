<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="text-start">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('filament-edit-profile::default.delete_account_card_description') }}
        </div>
    </div>
</x-dynamic-component>

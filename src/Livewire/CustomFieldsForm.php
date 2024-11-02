<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasUser;
use Throwable;

class CustomFieldsForm extends BaseProfileForm
{
    use HasUser;

    protected string $view = 'filament-edit-profile::livewire.custom-fields-form';

    public ?array $data = [];

    public ?array $customFields = [];

    protected static int $sort = 30;

    public function mount(): void
    {
        $this->user = $this->getUser();

        $data = $this->getUser()->attributesToArray();

        $this->customFields = config('filament-edit-profile.custom_fields');

        $this->form->fill($data['custom_fields'] ?? []);
    }

    public function form(Form $form): Form
    {
        $fields = array_map(
            [self::class, 'createField'],
            array_keys($this->customFields),
            $this->customFields
        );

        return $form
            ->schema([
                Forms\Components\Section::make(__('filament-edit-profile::default.custom_fields'))
                    ->aside()
                    ->description(__('filament-edit-profile::default.custom_fields_description'))
                    ->schema($fields),
            ])
            ->model($this->getUser())
            ->statePath('data');
    }

    private static function createField(string $fieldKey, array | Closure $field): ?Forms\Components\Component
    {
        switch ($field['type']) {
            case 'text':
                return self::createTextInput($fieldKey, $field);
            case 'password':
                return self::createPasswordInput($fieldKey, $field);
            case 'boolean':
                return self::createCheckbox($fieldKey, $field);
            case 'select':
                return self::createSelect($fieldKey, $field);
            case 'textarea':
                return self::createTextarea($fieldKey, $field);
            case 'datetime':
                return self::createDateTimePicker($fieldKey, $field);
            default:
                return self::createFieldFromString($fieldKey, $field);
        }
    }

    private static function createFieldFromString(string $fieldKey, array $field): ?Forms\Components\Component
    {
        try {

            $class = \Illuminate\Support\Str::camel($field['type']);
            $class = "Filament\Forms\Components\\{$class}";

            return $class::make($fieldKey)
                ->label(__($field['label']))
                ->placeholder(__($field['placeholder']))
                ->required($field['required'])
                ->rules($field['rules']);

        } catch (Throwable $exception) {
        }
    }

    private static function createTextInput(string $fieldKey, array $field): TextInput
    {
        return TextInput::make($fieldKey)
            ->label(__($field['label']))
            ->placeholder(__($field['placeholder']))
            ->required($field['required'])
            ->rules($field['rules']);
    }

    private static function createPasswordInput(string $fieldKey, array $field): TextInput
    {
        return TextInput::make($fieldKey)
            ->label(__($field['label']))
            ->placeholder(__($field['placeholder']))
            ->required($field['required'])
            ->password()
            ->rules($field['rules']);
    }

    private static function createCheckbox(string $fieldKey, array $field): Checkbox
    {
        return Checkbox::make($fieldKey)
            ->label(__($field['label']));
    }

    private static function createSelect(string $fieldKey, array $field): Select
    {
        return Select::make($fieldKey)
            ->label(__($field['label']))
            ->placeholder(__($field['placeholder']))
            ->options($field['options'])
            ->required($field['required']);
    }

    private static function createTextarea(string $fieldKey, array $field): Textarea
    {
        return Textarea::make($fieldKey)
            ->label(__($field['label']))
            ->placeholder(__($field['placeholder']))
            ->rows($field['rows'])
            ->required($field['required']);
    }

    private static function createDateTimePicker(string $fieldKey, array $field): DateTimePicker
    {
        return DateTimePicker::make($fieldKey)
            ->label(__($field['label']))
            ->placeholder(__($field['placeholder']))
            ->seconds($field['seconds']);
    }

    public function updateCustomFields(): void
    {
        try {
            $data = $this->form->getState();

            $data['custom_fields'] = $data ?? [];
            $customFields['custom_fields'] = $data['custom_fields'];

            $this->user->update($customFields);
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.saved_successfully'))
            ->send();
    }
}

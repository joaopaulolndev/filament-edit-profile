<?php

namespace Joaopaulolndev\FilamentEditProfile\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\text;

#[AsCommand(name: 'make:edit-profile-form')]
class FilamentEditProfileCommand extends Command
{
    use CanManipulateFiles;

    protected $signature = 'make:edit-profile-form {name?}';

    protected $description = 'Create a new Livewire component containing a Filament form';

    public function handle(): int
    {
        $component = (string) str($this->argument('name') ?? text(
            label: 'What is the form name?',
            placeholder: 'CustomEditProfileForm',
            required: true,
        ))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');
        $componentClass = (string) str($component)->afterLast('\\');
        $componentNamespace = str($component)->contains('\\') ?
            (string) str($component)->beforeLast('\\') :
            '';

        $view = str($component)
            ->replace('\\', '/')
            ->prepend('Livewire/')
            ->explode('/')
            ->map(fn ($segment) => Str::lower(Str::kebab($segment)))
            ->implode('.');

        $path = (string) str($component)
            ->prepend('/')
            ->prepend(app_path('Livewire/'))
            ->replace('\\', '/')
            ->replace('//', '/')
            ->append('.php');

        $viewPath = resource_path(
            (string) str($view)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        $this->copyStubToApp('EditForm', $path, [
            'class' => $componentClass,
            'namespace' => 'App\\Livewire' . ($componentNamespace !== '' ? "\\{$componentNamespace}" : ''),
            'view' => $view,
        ]);

        $this->copyStubToApp('FormView', $viewPath);

        $this->components->info("Filament Edit Profile form [{$path}] created successfully.");

        return static::SUCCESS;
    }
}

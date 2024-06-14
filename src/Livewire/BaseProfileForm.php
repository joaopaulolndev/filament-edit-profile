<?php

namespace Joaopaulolndev\FilamentEditProfile\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Livewire\Component;

class BaseProfileForm extends Component implements HasActions, HasForms
{
    use HasSort;
    use InteractsWithActions;
    use InteractsWithForms;

    protected static int $sort = 0;

    public function getName(): string
    {
        return Str::of(static::class)->afterLast('\\')->snake();
    }

    public function render(): View
    {
        return view($this->view);
    }
}

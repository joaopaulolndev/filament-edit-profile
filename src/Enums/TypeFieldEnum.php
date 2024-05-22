<?php

namespace Joaopaulolndev\FilamentEditProfile\Enums;

use Joaopaulolndev\FilamentEditProfile\Traits\WithOptions;

enum TypeFieldEnum: string
{
    use WithOptions;

    case Text = 'text';
    case Boolean = 'boolean';
    case Select = 'select';
    case Textarea = 'textarea';
    case Datetime = 'datetime';
}

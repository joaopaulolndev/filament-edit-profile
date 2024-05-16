<?php

namespace Joaopaulolndev\FilamentEditProfile\Commands;

use Illuminate\Console\Command;

class FilamentEditProfileCommand extends Command
{
    public $signature = 'filament-edit-profile';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

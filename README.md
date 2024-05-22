# Filament package to edit profile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)



The Filament library is a user-friendly tool that simplifies profile editing, offering an intuitive interface and robust features to easily customize and manage user information.

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/joaopaulolndev-filament-edit-profile.jpg)

## Features & Screenshots

- **Edit Information:** Manage your information such as email, and password.
- **Change Password:** Change your password.
- **Delete Account:** Manage your account, such as delete account. 
- **Custom Fields:** Add custom fields to the form.
- **Support**: [Laravel 11](https://laravel.com) and [Filament 3.x](https://filamentphp.com)

## Installation

You can install the package via composer:

```bash
composer require joaopaulolndev/filament-edit-profile
```

You can publish and run the migrations with:

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-edit-profile-views"
```

Optionally, you can publish the translations using

```bash
php artisan vendor:publish --tag="filament-edit-profile-translations"
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-edit-profile-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-edit-profile-config"
```

## Usage
Add in AdminPanelProvider.php
```php
->plugins([
    FilamentEditProfilePlugin::make()
])
```
if you want to show for specific parameters to sort, icon, title, navigation group, navigation label and can access, you can use the following example:
```php
 ->plugins([
     FilamentEditProfilePlugin::make()
        ->setTitle('My Profile')
        ->setNavigationLabel('My Profile')
        ->setNavigationGroup('Group Profile')
        ->setIcon('heroicon-o-user')
        ->setSort(10)
        ->canAccess(fn () => auth()->user()->id === 1)
        ->shouldRegisterNavigation(false)
        ->shouldShowDeleteAccountForm(false)
 ])
```

Optionally, you can add a user menu item to the user menu in the navigation bar:
```php
use Filament\Navigation\MenuItem;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

->userMenuItems([
    'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
                    //If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                    ->visible(function (): bool {
                        return auth()->user()->company()->exists();
                    })
    ,
])
```

## Custom Fields
![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/custom_fields.png)
Optionally, you can add custom fields to the form.
To create custom fields you need to follow the steps below:

1. Publish the migration file to add the custom fields to the users table:
```bash
php artisan vendor:publish --tag="filament-edit-profile-migrations"
php artisan migrate
```
2. Add in your User model the custom field in the fillable array:
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'custom_fields',
];
```
3. Add in your User model the custom field in the casts array:
```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'custom_fields' => 'array'
    ];
}
```

5. Publish the config file using this command:

```bash
php artisan vendor:publish --tag="filament-edit-profile-config"
```

6. Edit the config file `config/filament-edit-profile.php` to add the custom fields to the form as example below:

```php
<?php

return [
        'show_custom_fields' => true,
        'custom_fields' => [
            'custom_field_1' => [
                'type' => 'text',
                'label' => 'Custom Textfield 1',
                'placeholder' => 'Custom Field 1',
                'required' => true,
                'rules' => 'required|string|max:255',
            ],
            'custom_field_2' => [
                'type' => 'select',
                'label' => 'Custom Select 2',
                'placeholder' => 'Select',
                'required' => true,
                'options' => [
                    'option_1' => 'Option 1',
                    'option_2' => 'Option 2',
                    'option_3' => 'Option 3',
                ],
            ],
            'custom_field_3' => [
                'type' =>'textarea',
                'label' => 'Custom Textarea 3',
                'placeholder' => 'Textarea',
                'rows' => '3',
                'required' => true,
            ],
            'custom_field_4' => [
                'type' => 'datetime',
                'label' => 'Custom Datetime 4',
                'placeholder' => 'Datetime',
                'seconds' => false,
            ],
            'custom_field_5' => [
                'type' => 'boolean',
                'label' => 'Custom Boolean 5',
                'placeholder' => 'Boolean'
            ],
        ]
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [João Paulo Leite Nascimento](https://github.com/joaopaulolndev)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

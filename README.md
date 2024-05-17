# Filament package to edit profile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)



The Filament library is a user-friendly tool that simplifies profile editing, offering an intuitive interface and robust features to easily customize and manage user information.

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/joaopaulolndev-screen.png)

## Features & Screenshots

- **Edit Information:** Manage your information such as email, and password.
- **Change Password:** Change your password.
- **Delete Account:** Manage your account, such as delete account.
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
php artisan vendor:publish --tag="filament-general-settings-translations"
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

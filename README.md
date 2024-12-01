# Filament package to edit profile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)

The Filament library is a user-friendly tool that simplifies profile editing, offering an intuitive interface and robust features to easily customize and manage user information.
<div class="filament-hidden">
    
![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/joaopaulolndev-filament-edit-profile.jpg)

</div>

## Features & Screenshots

-   **Edit Information:** Manage your information such as email, and password.
-   **Change Password:** Change your password.
-   **Profile Photo:** Upload and manage your profile photo.
-   **Delete Account:** Manage your account, such as delete account.
-   **Sanctum Personal Access tokens:** Manage your personal access tokens.
-   **Browser Sessions** Manage and log out your active sessions on other browsers and devices.
-   **Custom Fields:** Add custom fields to the form.
-   **Custom Components:** Add custom component to the page.
-   **Support**: [Laravel 11](https://laravel.com) and [Filament 3.x](https://filamentphp.com)

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

You can publish and run all the migrations with:

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
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

->plugins([
    FilamentEditProfilePlugin::make()
])
```

if you want to show for specific parameters to sort, icon, title, navigation group, navigation label and can access, you can use the following example:

```php
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

 ->plugins([
     FilamentEditProfilePlugin::make()
        ->slug('my-profile')
        ->setTitle('My Profile')
        ->setNavigationLabel('My Profile')
        ->setNavigationGroup('Group Profile')
        ->setIcon('heroicon-o-user')
        ->setSort(10)
        ->canAccess(fn () => auth()->user()->id === 1)
        ->shouldRegisterNavigation(false)
        ->shouldShowDeleteAccountForm(false)
        ->shouldShowSanctumTokens()
        ->shouldShowBrowserSessionsForm()
        ->shouldShowAvatarForm()
        ->customProfileComponents([
            \App\Livewire\CustomProfileComponent::class,
        ])
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
        }),
])
```

If needed you can define the disk and visibility of the avatar image. In the config file add the following:

[config/filament-edit-profile.php](config/filament-edit-profile.php)

```php
return [
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public', // or replace by filesystem disk visibility with fallback value
];
```


## Profile Avatar

![Screenshot of avatar Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/profile-avatar.png)
Show the user avatar form using `shouldShowAvatarForm()`. This package follows the [Filament user avatar](https://filamentphp.com/docs/3.x/panels/users#setting-up-user-avatars) to manage the avatar.

To show the avatar form, you need the following steps:

1. Publish the migration file to add the avatar_url field to the users table:

```bash
php artisan vendor:publish --tag="filament-edit-profile-avatar-migration"
php artisan migrate
```

2. Add in your User model the avatar_url field in the fillable array:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'avatar_url', // or column name according to config('filament-edit-profile.avatar_column', 'avatar_url')
];
```

3. Set the getFilamentAvatarUrlAttribute method in your User model:

```php
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements HasAvatar
{
    // ...
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url("$this->$avatarColumn") : null;
    }
}
```

4. Optionally, you can specify the image directory path and file upload rules. :

```php
->shouldShowAvatarForm(
    value: true,
    directory: 'avatars', // image will be stored in 'storage/app/public/avatars
    rules: 'mimes:jpeg,png|max:1024' //only accept jpeg and png files with a maximum size of 1MB
)
```

5. Don't forget to run the command `php artisan storage:link`

## Sanctum Personal Access tokens

Show the Sanctum token management component:

Please review [Laravel Sanctum Docs](https://laravel.com/docs/11.x/sanctum)

You may install Laravel Sanctum via the `install:api` Artisan command:

```bash
php artisan install:api
```

Sanctum allows you to issue API tokens / personal access tokens that may be used to authenticate API requests to your application. When making requests using API tokens, the token should be included in the Authorization header as a Bearer token.

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/sanctum_tokens.png)

If you want to control access, you can use `condition`, passing Closure or Boolean

Sanctum allows you to assign "abilities" to tokens. by default we have ['create', 'view', 'update', 'delete'] use `permissions` to customize

```php
 ->plugins([
    FilamentEditProfilePlugin::make()
        ->shouldShowSanctumTokens(
            condition: fn() => auth()->user()->id === 1, //optional
            permissions: ['custom', 'abilities', 'permissions'] //optional
        )
 ])
```

## Browser Sessions

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/browser-sessions.png)

To utilize browser session, ensure that your session configuration's driver (or SESSION_DRIVER environment variable) is set to `database`.

```env
SESSION_DRIVER=database
```

If you want to control access or disable browser sessions, you can pass a Closure or Boolean

```php
 ->plugins([
    FilamentEditProfilePlugin::make()
        ->shouldShowBrowserSessionsForm(
            fn() => auth()->user()->id === 1, //optional
                //OR
            false //optional
        )
 ])
```

## Custom Fields

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/custom_fields.png)
Optionally, you can add custom fields to the form.
To create custom fields you need to follow the steps below:

1. Publish the migration file to add the custom fields to the users table:

```bash
php artisan vendor:publish --tag="filament-edit-profile-custom-field-migration"
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
            'type' => 'password',
            'label' => 'Custom Password field 2',
            'placeholder' => 'Custom Password Field 2',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
        'custom_field_3' => [
            'type' => 'select',
            'label' => 'Custom Select 3',
            'placeholder' => 'Select',
            'required' => true,
            'options' => [
                'option_1' => 'Option 1',
                'option_2' => 'Option 2',
                'option_3' => 'Option 3',
            ],
        ],
        'custom_field_4' => [
            'type' =>'textarea',
            'label' => 'Custom Textarea 4',
            'placeholder' => 'Textarea',
            'rows' => '3',
            'required' => true,
        ],
        'custom_field_5' => [
            'type' => 'datetime',
            'label' => 'Custom Datetime 5',
            'placeholder' => 'Datetime',
            'seconds' => false,
        ],
        'custom_field_6' => [
            'type' => 'boolean',
            'label' => 'Custom Boolean 6',
            'placeholder' => 'Boolean'
        ],
    ]
];
```

## Custom Components

If you need more control over your profile edit fields, you can create a custom component. To make this process easier, just use the artisan command.

> [!NOTE]
> If you are not confident in using custom components, please review [Filament Docs](https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component)

```bash
php artisan make:edit-profile-form CustomProfileComponent
```

This will generate a new `app/Livewire/CustomProfileComponent.php` component and a new `resources/views/livewire/custom-profile-component.blade.php` view which you can customize.

Now in your `Panel Provider`, register the new component.

```php
->plugins([
    FilamentEditProfilePlugin::make()
        ->customProfileComponents([
            \App\Livewire\CustomProfileComponent::class,
        ]);
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

-   [João Paulo Leite Nascimento](https://github.com/joaopaulolndev)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

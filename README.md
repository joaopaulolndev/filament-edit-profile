# Filament package to edit profile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/run-tests.yml?branch=2.x&label=tests&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3Arun-tests+branch%3A2.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/joaopaulolndev/filament-edit-profile/fix-php-code-style-issues.yml?branch=2.x&label=code%20style&style=flat-square)](https://github.com/joaopaulolndev/filament-edit-profile/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3A2.x)
[![Total Downloads](https://img.shields.io/packagist/dt/joaopaulolndev/filament-edit-profile.svg?style=flat-square)](https://packagist.org/packages/joaopaulolndev/filament-edit-profile)

The Filament library is a user-friendly tool that simplifies profile editing, offering an intuitive interface and robust features to easily customize and manage user information.
<div class="filament-hidden">
    
![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/2.x/art/joaopaulolndev-filament-edit-profile.jpg)

</div>

## Features & Screenshots

-   **Edit Information:** Manage your information such as email, password, locale, theme color.
-   **Change Password:** Change your password.
-   **Profile Photo:** Upload and manage your profile photo.
-   **Delete Account:** Manage your account, such as delete account.
-   **Sanctum Personal Access tokens:** Manage your personal access tokens.
-   **Multi Factor Authentication:** Manage multi factor authentication.
-   **Browser Sessions** Manage and log out your active sessions on other browsers and devices.
-   **Custom Fields:** Add custom fields to the form.
-   **Custom Components:** Add custom component to the page.
-   **Support**: [Laravel 11](https://laravel.com) and [Filament 3.x](https://filamentphp.com)

## Compatibility

| Package Version | Filament Version |
|-----------------|------------------|
| 1.x             | 3.x              |
| 2.x             | 4.x              |

## Installation

You can install the package via composer:

```bash
composer require joaopaulolndev/filament-edit-profile:^2.0
```

**Filament V3** - if you are using Filament v3.x, you can use [this section](https://github.com/joaopaulolndev/filament-edit-profile/tree/main)

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
        ->shouldShowEmailForm()
        ->shouldShowLocaleForm(
            options: [
                'pt_BR' => '🇧🇷 Português',
                'en' => '🇺🇸 Inglês',
                'es' => '🇪🇸 Espanhol',
            ],
            rules: 'required' // optional validation rules for the locale field
        )
        ->shouldShowThemeColorForm(rules: 'required') // optional validation rules for the theme color field
        ->shouldShowDeleteAccountForm(false)
        ->shouldShowSanctumTokens()
        ->shouldShowMultiFactorAuthentication()
        ->shouldShowBrowserSessionsForm()
        ->shouldShowAvatarForm()
        ->customProfileComponents([
            \App\Livewire\CustomProfileComponent::class,
        ])
 ])
```

Optionally, you can add a user menu item to the user menu in the navigation bar:

```php
use Filament\Actions\Action;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

->userMenuItems([
    'profile' => Action::make('profile')
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

![Screenshot of avatar Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/2.x/art/profile-avatar.png)
Show the user avatar form using `shouldShowAvatarForm()`. This package follows the [Filament user avatar](https://filamentphp.com/docs/4.x/users/overview#setting-up-user-avatars) to manage the avatar.

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
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
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

## Profile Locale

Show the user locale form using `shouldShowLocaleForm()`. You can now customize validation rules for this field using the `rules` parameter (e.g., 'required'). If you don't set rules, the field is not required by default.

To show the locale form, you need the following steps:

1. Publish the migration file to add the locale field to the users table:

```bash
php artisan vendor:publish --tag="filament-edit-profile-locale-migration"
php artisan migrate
```

2. Update the options array with the languages you want to show:

```php
->shouldShowLocaleForm(
    options: [
        'pt_BR' => '🇧🇷 Português',
        'en' => '🇺🇸 Inglês',
        'es' => '🇪🇸 Espanhol',
    ],
    rules: 'required' // optional validation rules, e.g. 'required|in:pt_BR,en,es'
)
```

3. Add in your User model the locale field in the fillable array:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'locale', // or column name according to config('filament-edit-profile.locale_column', 'locale')
];
```

## Profile Theme Color

Show the user theme_color form using `shouldShowThemeColorForm()`. You can now customize validation rules for this field using the `rules` parameter (e.g., 'required'). If you don't set rules, the field is not required by default.

To show the theme_color form, you need the following steps:

1. Publish the migration file to add the theme_color field to the users table:

```bash
php artisan vendor:publish --tag="filament-edit-profile-theme-color-migration"
php artisan migrate
```

2. Update the primary color default value:

```php
->shouldShowThemeColorForm(rules: 'required') // optional validation rules, e.g. 'required|regex:/^#?[0-9a-fA-F]{6}$/'
```

3. Add in your User model the locale field in the fillable array:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'theme_color', // or column name according to config('filament-edit-profile.theme_color_column', 'theme_color')
];
```

## Email Change Verification

The `filament-edit-profile` plugin is fully compatible with the `emailChangeVerification` feature introduced in Filament.

When this feature is enabled in your Panel Provider, the plugin ensures that an email address change is only finalized after the user confirms ownership of the new email address.

### How It Works

1.  A user navigates to the edit profile page provided by this plugin.
2.  They change their email address and click "Save".
3.  The plugin, using Filament's core logic, **does not update the email in the database immediately**.
4.  Instead, a verification email with a signed link is sent to the **new** email address.
5.  The user's email in the database is only updated after they click the confirmation link sent to the new address.

This prevents users from being locked out of their accounts if they enter an incorrect email address, and it stops malicious attempts to take over an account by changing the owner's email without permission.

### How to Enable

You don't need to change any code within the `filament-edit-profile` plugin to enable this feature. Simply follow the official Filament documentation steps:

1.  **Enable verification in your Panel Provider:**

    In your Panel Provider file (e.g., `app/Providers/Filament/AdminPanelProvider.php`), add the `emailChangeVerification()` method to the panel configuration:

    ```php
    use Filament\Panel;
    use Filament\PanelProvider;

    class AdminPanelProvider extends PanelProvider
    {
        public function panel(Panel $panel): Panel
        {
            return $panel
                // ...
                ->emailChangeVerification();
        }
    }
    ```

That's it\! The `filament-edit-profile` plugin will now respect this configuration and trigger the email verification flow automatically.

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

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/2.x/art/sanctum_tokens.png)

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

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/2.x/art/browser-sessions.png)

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

## Multi-Factor Authentication (MFA)

This plugin integrates with Filament's native Multi-Factor Authentication (MFA) system, allowing your users to manage their MFA settings directly from the profile edit page.

### Required Setup

For the MFA functionality to be available on the profile page, **you must first set up MFA in your Filament panel** by following the official documentation.

This typically involves running a database migration and adding the `TwoFactorAuthenticatable` trait to your `User` model. You can find the detailed instructions at the link below:

➡️ **[Official Filament MFA Documentation](https://filamentphp.com/docs/4.x/users/multi-factor-authentication)**

### Controlling the MFA Section's Visibility

After setting up MFA in your project, the MFA section will be displayed by default for all users. However, this plugin provides a method to dynamically control who can see and manage the MFA options.

In your panel provider file (usually `app/Providers/Filament/AdminPanelProvider.php`), you can use the `shouldShowMultiFactorAuthentication()` method in two ways:

#### 1\. Display based on a condition (Closure)

You can pass a `Closure` that returns `true` or `false`. The MFA section will only be displayed if the condition is met. In the example below, only the user with ID `1` will be able to see the MFA options.

```php
 ->plugins([
    FilamentEditProfilePlugin::make()
        ->shouldShowMultiFactorAuthentication(
            // The section will only be visible to the user with ID 1.
            fn() => auth()->user()->id === 1, //optional
                //OR
            false //optional
        )
 ])
```

#### 2\. Disable it completely

If you want to hide the MFA section for all users through the profile page, simply pass `false` as the argument.

```php
 ->plugins([
    FilamentEditProfilePlugin::make()
        ->shouldShowMultiFactorAuthentication(false)
 ])
```

> **Note:** If the `shouldShowMultiFactorAuthentication()` method is not called, the default behavior is to display the MFA section for all users (equivalent to passing `true`), provided that the required setup has been completed correctly.

## Custom Fields

![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/2.x/art/custom_fields.png)
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
            'type' => 'text', // required
            'label' => 'Custom Textfield 1', // required
            'placeholder' => 'Custom Field 1', // optional
            'id' => 'custom-field-1', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
        ],
        'custom_field_2' => [
            'type' => 'password', // required
            'label' => 'Custom Password field 2', // required
            'placeholder' => 'Custom Password Field 2', // optional
            'id' => 'custom-field-2', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'default' => '', // optional
            'column_span' => 'full',
            'revealable' => true, // optional
            'autocomplete' => true, // optional
        ],
        'custom_field_3' => [
            'type' => 'select', // required
            'label' => 'Custom Select 3', // required
            'placeholder' => 'Select', // optional
            'id' => 'custom-field-3', // optional
            'required' => true, // optional
            'options' => [
                'option_1' => 'Option 1',
                'option_2' => 'Option 2',
                'option_3' => 'Option 3',
            ], // optional
            'selectable_placeholder' => true // optional
            'native' => true // optional
            'preload' => true // optional
            'suffix_icon' => '', // optional
            'default' => '', // optional
            'searchable' => true, // optional
            'column_span' => 'full', // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
        ],
        'custom_field_4' => [
            'type' =>'textarea', // required
            'label' => 'Custom Textarea 4', // required
            'placeholder' => 'Textarea', // optional
            'id' => 'custom-field-4', // optional
            'rows' => '3', // optional
            'required' => true, // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'default' => '', // optional
            'rules' => [], // optional
            'column_span' => 'full', // optional
        ],
        'custom_field_5' => [
            'type' => 'datetime', // required
            'label' => 'Custom Datetime 5', // required
            'placeholder' => 'Datetime', // optional
            'id' => 'custom-field-5', // optional
            'seconds' => false, // optional
            'required' => true, // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'default' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'rules' => [], // optional
            'format' => 'Y-m-d H:i:s', // optional
            'time' => true, // optional
            'native' => true, // optional
            'column_span' => 'full', // optional
        ],
        'custom_field_6' => [
            'type' => 'boolean', // required
            'label' => 'Custom Boolean 6', // required
            'placeholder' => 'Boolean', // optional
            'id' => 'custom-field-6', // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'default' => '', // optional
            'rules' => [], // optional
            'column_span' => 'full', // optional
        ],
    ]
];
```

## Custom Components

If you need more control over your profile edit fields, you can create a custom component. To make this process easier, just use the artisan command.

> [!NOTE]
> If you are not confident in using custom components, please review [Filament Docs](https://filamentphp.com/docs/4.x/components/form)

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

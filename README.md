## Enhancements Added in This Fork

This package is a fork of [joaopaulolndev/filament-edit-profile](https://filamentphp.com/plugins/joaopaulolndev-edit-profile) by João Paulo Leite Nascimento. We extend our gratitude to the original author for creating such a robust profile management solution for Filament.

### Email Management Options

We've added enhanced control over email address management:

1. **Toggle Email Editing**: Ability to enable or disable email editing functionality
2. **Email Change Verification**: Optional email verification process when changing email addresses

On email confirmation there is a redirect after the confirmation, that url can be overriden with "'redirectUrl' => '/admin/edit-profile'," on the cofings file after it is published.

By default, the following configuration is used:

```php
public bool $shouldEditEmail = true;
public bool $shouldConfirmEmail = false;
```

#### Implementation

Use these methods in your panel provider to customize email behavior:

```php
use NoopStudios\FilamentEditProfile\FilamentEditProfilePlugin;

 ->plugins([
     FilamentEditProfilePlugin::make()
        ->shouldEditEmail(true) // Enable or disable email editing
        ->shouldConfirmEmail(true) // Enable or disable email verification
 ])
```

When email verification is enabled, users receive a verification link to confirm their email change before it takes effect, enhancing security for your application.

### Additional Improvements

- Changed the avatar logic to use Spatie Media Library for more robust image management
- Redesigned the profile interface to use a tab layout for better organization of profile sections
  - Refactored `EditProfilePage.php` to support the new layout

<div class="filament-hidden">
    
![Screenshot of Application Feature](https://raw.githubusercontent.com/joaopaulolndev/filament-edit-profile/main/art/joaopaulolndev-filament-edit-profile.jpg)


## Credits

-   [João Paulo Leite Nascimento](https://github.com/joaopaulolndev)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

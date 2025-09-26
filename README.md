# Laravel Breadcrumbs

A simple and flexible breadcrumb package for Laravel 12+ applications that provides an easy way to manage and display breadcrumb navigation using FluxUI components.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/act-training/laravel-breadcrumbs.svg?style=flat-square)](https://packagist.org/packages/act-training/laravel-breadcrumbs)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/act-training/laravel-breadcrumbs/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/act-training/laravel-breadcrumbs/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/act-training/laravel-breadcrumbs.svg?style=flat-square)](https://packagist.org/packages/act-training/laravel-breadcrumbs)

## Features

- Simple and intuitive breadcrumb definition
- Support for nested breadcrumbs with parent relationships
- Automatic breadcrumb generation from current route
- Built-in FluxUI component integration
- Special Dashboard icon support
- Artisan command for generating breadcrumb definitions
- Full Pest test coverage
- Laravel 12+ only support

## Requirements

- PHP 8.2+
- Laravel 12+
- FluxUI (Livewire Flux)

## Installation

You can install the package via composer:

```bash
composer require act-training/laravel-breadcrumbs
```

The package will automatically register its service provider.

**Note:** This package requires FluxUI to be installed and configured in your Laravel application.

You can publish the config file with:

```bash
php artisan vendor:publish --tag="breadcrumbs-config"
```

You can publish the views with:

```bash
php artisan vendor:publish --tag="breadcrumbs-views"
```

## Usage

### Defining Breadcrumbs

Create a `routes/breadcrumbs.php` file to define your breadcrumbs:

```php
<?php

use ActTraining\LaravelBreadcrumbs\Facades\Breadcrumbs;

// Dashboard
Breadcrumbs::define('dashboard', function ($trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Users Index
Breadcrumbs::define('users.index', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('users.index'));
});

// User Show
Breadcrumbs::define('users.show', function ($trail, $user) {
    $trail->parent('users.index');
    $trail->push($user->name, route('users.show', $user));
});
```

### Displaying Breadcrumbs

In your Blade templates, use the breadcrumbs component:

```blade
<!-- Automatically generate from current route -->
<x-breadcrumbs />

<!-- Or specify a specific route -->
<x-breadcrumbs route="users.show" :params="[$user]" />
```

### Artisan Command

Generate breadcrumb definitions using the Artisan command:

```bash
php artisan make:breadcrumb users.edit --parent=users.show --title="Edit User"
```

### Manual Usage

You can also generate breadcrumbs manually:

```php
use ActTraining\LaravelBreadcrumbs\Facades\Breadcrumbs;

// Generate breadcrumbs for a specific route
$breadcrumbs = Breadcrumbs::generate('users.show', $user);

// Generate breadcrumbs from current route
$breadcrumbs = Breadcrumbs::generateFromRoute();

// Check if breadcrumb exists
if (Breadcrumbs::exists('users.show')) {
    // ...
}
```

## Configuration

The package comes with a configuration file that allows you to customize various aspects:

```php
return [
    'definitions_file' => base_path('routes/breadcrumbs.php'),
    'view' => 'breadcrumbs::breadcrumbs',
    'skip_single_item' => true,
    'separator' => '/',
    'classes' => [
        'wrapper' => 'breadcrumbs',
        'list' => 'breadcrumb-list',
        'item' => 'breadcrumb-item',
        'link' => 'breadcrumb-link',
        'active' => 'breadcrumb-active',
        'separator' => 'breadcrumb-separator',
    ],
];
```

### Customizing Views

You can customize the breadcrumb HTML by publishing the views and modifying them:

```bash
php artisan vendor:publish --tag="breadcrumbs-views"
```

The default view will be published to `resources/views/vendor/breadcrumbs/breadcrumbs.blade.php`.

### FluxUI Integration

The package uses FluxUI components for rendering breadcrumbs:

- `<flux:breadcrumbs>` for the main breadcrumb container
- `<flux:breadcrumbs.item>` for individual breadcrumb items
- `<flux:icon icon="house">` for the special Dashboard icon

The default styling includes:
- Orange accent color for Dashboard items
- Responsive text sizing
- Dark mode support
- Hover states

## Advanced Usage

### Complex Nested Breadcrumbs

```php
Breadcrumbs::define('admin.users.roles.edit', function ($trail, $user, $role) {
    $trail->parent('admin.users.show', $user);
    $trail->push('Edit Role: ' . $role->name, route('admin.users.roles.edit', [$user, $role]));
});
```

### Conditional Breadcrumbs

```php
Breadcrumbs::define('posts.show', function ($trail, $post) {
    if ($post->category) {
        $trail->parent('categories.show', $post->category);
    } else {
        $trail->parent('posts.index');
    }

    $trail->push($post->title, route('posts.show', $post));
});
```

### Dashboard Icon Support

The package includes special handling for Dashboard breadcrumbs:

```php
// When you define a breadcrumb with the title 'Dashboard'
Breadcrumbs::define('dashboard', function ($trail) {
    $trail->push('Dashboard', route('dashboard'));
});
```

It will automatically render with a house icon instead of text:

```blade
<!-- Automatically renders as -->
<flux:breadcrumbs.item href="/dashboard">
    <flux:icon icon="house" class="size-5 !text-orange-500 hover:!text-orange-600" />
</flux:breadcrumbs.item>
```

## Testing

The package uses Pest for testing:

```bash
composer test
```

Or run Pest directly:

```bash
vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ACT Training](https://github.com/act-training)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
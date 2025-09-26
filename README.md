# Laravel Breadcrumbs

A simple and flexible breadcrumb package for Laravel applications that provides an easy way to manage and display breadcrumb navigation.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/act-training/laravel-breadcrumbs.svg?style=flat-square)](https://packagist.org/packages/act-training/laravel-breadcrumbs)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/act-training/laravel-breadcrumbs/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/act-training/laravel-breadcrumbs/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/act-training/laravel-breadcrumbs.svg?style=flat-square)](https://packagist.org/packages/act-training/laravel-breadcrumbs)

## Features

- Simple and intuitive breadcrumb definition
- Support for nested breadcrumbs with parent relationships
- Automatic breadcrumb generation from current route
- Customizable views and CSS classes
- Artisan command for generating breadcrumb definitions
- Framework-agnostic HTML output
- Full test coverage

## Installation

You can install the package via composer:

```bash
composer require act-training/laravel-breadcrumbs
```

The package will automatically register its service provider.

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

### CSS Styling

The package generates semantic HTML with configurable CSS classes. Here's a basic CSS example:

```css
.breadcrumbs {
    margin-bottom: 1rem;
}

.breadcrumb-list {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-link {
    color: #3b82f6;
    text-decoration: none;
}

.breadcrumb-link:hover {
    text-decoration: underline;
}

.breadcrumb-active {
    color: #6b7280;
}

.breadcrumb-separator {
    margin: 0 0.5rem;
    color: #9ca3af;
}
```

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

### Using with Different UI Frameworks

The package is framework-agnostic, but you can easily integrate it with popular CSS frameworks:

#### Bootstrap 5
```blade
@if(count($breadcrumbs) > 1)
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                @if($breadcrumb->url && !$loop->last)
                    <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                @else
                    {{ $breadcrumb->title }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
```

#### Tailwind CSS
```blade
@if(count($breadcrumbs) > 1)
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($breadcrumbs as $breadcrumb)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
                @if($breadcrumb->url && !$loop->last)
                    <a href="{{ $breadcrumb->url }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                        {{ $breadcrumb->title }}
                    </a>
                @else
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                        {{ $breadcrumb->title }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
```

## Testing

```bash
composer test
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
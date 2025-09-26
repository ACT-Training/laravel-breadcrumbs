<?php

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

beforeEach(function () {
    // Clear any existing breadcrumb definitions before each test
    $reflection = new ReflectionClass(Breadcrumbs::class);
    $property = $reflection->getProperty('breadcrumbs');
    $property->setAccessible(true);
    $property->setValue(null, []);
});

it('renders breadcrumbs component', function () {
    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    Breadcrumbs::define('users.index', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Users', '/users');
    });

    $view = $this->blade('<x-breadcrumbs route="users.index" />');

    $view->assertSee('Dashboard');
    $view->assertSee('Users');
    $view->assertSee('flux:breadcrumbs');
});

it('generates breadcrumbs from current route', function () {
    Route::get('/test', function () {
        return 'test';
    })->name('test.route');

    Breadcrumbs::define('test.route', function ($trail) {
        $trail->push('Test Page', '/test');
    });

    $this->get('/test');

    $view = $this->blade('<x-breadcrumbs />');

    $view->assertSee('Test Page');
});

it('skips single item breadcrumbs by default', function () {
    Breadcrumbs::define('single', function ($trail) {
        $trail->push('Single Item', '/single');
    });

    $view = $this->blade('<x-breadcrumbs route="single" />');

    $view->assertDontSee('Single Item');
});

it('can show single item breadcrumbs when configured', function () {
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('single', function ($trail) {
        $trail->push('Single Item', '/single');
    });

    $view = $this->blade('<x-breadcrumbs route="single" />');

    $view->assertSee('Single Item');
});

it('renders dashboard breadcrumb with house icon', function () {
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    $view = $this->blade('<x-breadcrumbs route="dashboard" />');

    $view->assertSee('flux:icon');
    $view->assertSee('house');
    $view->assertSee('text-orange-500');
});

it('renders multiple breadcrumbs with flux components', function () {
    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    Breadcrumbs::define('users.show', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('John Doe', '/users/1');
    });

    $view = $this->blade('<x-breadcrumbs route="users.show" />');

    $view->assertSee('flux:breadcrumbs.item');
    $view->assertSee('house');
    $view->assertSee('John Doe');
});
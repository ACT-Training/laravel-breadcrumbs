<?php

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    // Clear any existing breadcrumb definitions before each test
    $reflection = new ReflectionClass(Breadcrumbs::class);
    $property = $reflection->getProperty('breadcrumbs');
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

    $view->assertSee('house'); // Should see the house icon for Dashboard
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

it('respects home_display config set to text', function () {
    config(['breadcrumbs.home_display' => 'text']);
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    $view = $this->blade('<x-breadcrumbs route="dashboard" />');

    $view->assertSee('Dashboard');
    $view->assertDontSee('flux:icon');
});

it('respects home_display config set to both', function () {
    config(['breadcrumbs.home_display' => 'both']);
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    $view = $this->blade('<x-breadcrumbs route="dashboard" />');

    $view->assertSee('Dashboard');
    $view->assertSee('flux:icon');
    $view->assertSee('house');
});

it('respects home_display config set to icon', function () {
    config(['breadcrumbs.home_display' => 'icon']);
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    $view = $this->blade('<x-breadcrumbs route="dashboard" />');

    $view->assertSee('flux:icon');
    $view->assertSee('house');
    $view->assertDontSee('Dashboard');
});

it('respects custom home_icon config', function () {
    config(['breadcrumbs.home_icon' => 'home']);
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    $view = $this->blade('<x-breadcrumbs route="dashboard" />');

    $view->assertSee('flux:icon');
    $view->assertSee('home');
    $view->assertDontSee('house');
});

it('respects custom home_route config', function () {
    config(['breadcrumbs.home_route' => 'home']);
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('home', function ($trail) {
        $trail->push('Home', '/home');
    });

    $view = $this->blade('<x-breadcrumbs route="home" />');

    $view->assertSee('flux:icon');
    $view->assertSee('house');
    $view->assertSee('text-orange-500');
});

it('does not apply home styling to non-home routes', function () {
    config(['breadcrumbs.home_route' => 'dashboard']);
    config(['breadcrumbs.skip_single_item' => false]);

    Breadcrumbs::define('about', function ($trail) {
        $trail->push('About', '/about');
    });

    $view = $this->blade('<x-breadcrumbs route="about" />');

    $view->assertDontSee('flux:icon');
    $view->assertDontSee('house');
    $view->assertSee('About');
});
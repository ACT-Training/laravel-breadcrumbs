<?php

use ActTraining\LaravelBreadcrumbs\BreadcrumbGenerator;
use ActTraining\LaravelBreadcrumbs\Breadcrumbs;
use ReflectionClass;

beforeEach(function () {
    // Clear any existing breadcrumb definitions before each test
    $reflection = new ReflectionClass(Breadcrumbs::class);
    $property = $reflection->getProperty('breadcrumbs');
    $property->setAccessible(true);
    $property->setValue(null, []);
});

it('can define and generate simple breadcrumbs', function () {
    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    $breadcrumbs = Breadcrumbs::generate('dashboard');

    expect($breadcrumbs)->toHaveCount(1);
    expect($breadcrumbs[0]->title)->toBe('Dashboard');
    expect($breadcrumbs[0]->url)->toBe('/dashboard');
});

it('can generate breadcrumbs with parent relationships', function () {
    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    Breadcrumbs::define('users.index', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Users', '/users');
    });

    $breadcrumbs = Breadcrumbs::generate('users.index');

    expect($breadcrumbs)->toHaveCount(2);
    expect($breadcrumbs[0]->title)->toBe('Dashboard');
    expect($breadcrumbs[0]->url)->toBe('/dashboard');
    expect($breadcrumbs[1]->title)->toBe('Users');
    expect($breadcrumbs[1]->url)->toBe('/users');
});

it('can generate breadcrumbs with parameters', function () {
    $user = (object) ['id' => 1, 'name' => 'John Doe'];

    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    Breadcrumbs::define('users.index', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Users', '/users');
    });

    Breadcrumbs::define('users.show', function ($trail, $user) {
        $trail->parent('users.index');
        $trail->push($user->name, "/users/{$user->id}");
    });

    $breadcrumbs = Breadcrumbs::generate('users.show', $user);

    expect($breadcrumbs)->toHaveCount(3);
    expect($breadcrumbs[0]->title)->toBe('Dashboard');
    expect($breadcrumbs[1]->title)->toBe('Users');
    expect($breadcrumbs[2]->title)->toBe('John Doe');
    expect($breadcrumbs[2]->url)->toBe('/users/1');
});

it('can generate breadcrumbs with null urls', function () {
    Breadcrumbs::define('dashboard', function ($trail) {
        $trail->push('Dashboard', '/dashboard');
    });

    Breadcrumbs::define('users.create', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Create User', null);
    });

    $breadcrumbs = Breadcrumbs::generate('users.create');

    expect($breadcrumbs)->toHaveCount(2);
    expect($breadcrumbs[0]->title)->toBe('Dashboard');
    expect($breadcrumbs[0]->url)->toBe('/dashboard');
    expect($breadcrumbs[1]->title)->toBe('Create User');
    expect($breadcrumbs[1]->url)->toBeNull();
});

it('returns empty array for undefined routes', function () {
    $breadcrumbs = Breadcrumbs::generate('undefined.route');

    expect($breadcrumbs)->toBeEmpty();
});

it('can check if breadcrumb exists', function () {
    Breadcrumbs::define('test.route', function ($trail) {
        $trail->push('Test', '/test');
    });

    expect(Breadcrumbs::exists('test.route'))->toBeTrue();
    expect(Breadcrumbs::exists('nonexistent.route'))->toBeFalse();
});

it('can forget breadcrumb definitions', function () {
    Breadcrumbs::define('test.route', function ($trail) {
        $trail->push('Test', '/test');
    });

    expect(Breadcrumbs::exists('test.route'))->toBeTrue();

    Breadcrumbs::forget('test.route');

    expect(Breadcrumbs::exists('test.route'))->toBeFalse();
});

it('can clear all breadcrumb definitions', function () {
    Breadcrumbs::define('test1', function ($trail) {
        $trail->push('Test 1', '/test1');
    });

    Breadcrumbs::define('test2', function ($trail) {
        $trail->push('Test 2', '/test2');
    });

    expect(Breadcrumbs::exists('test1'))->toBeTrue();
    expect(Breadcrumbs::exists('test2'))->toBeTrue();

    Breadcrumbs::clear();

    expect(Breadcrumbs::exists('test1'))->toBeFalse();
    expect(Breadcrumbs::exists('test2'))->toBeFalse();
});

it('can get all breadcrumb definitions', function () {
    Breadcrumbs::define('test1', function ($trail) {
        $trail->push('Test 1', '/test1');
    });

    Breadcrumbs::define('test2', function ($trail) {
        $trail->push('Test 2', '/test2');
    });

    $all = Breadcrumbs::all();

    expect($all)->toHaveKey('test1');
    expect($all)->toHaveKey('test2');
    expect($all)->toHaveCount(2);
});
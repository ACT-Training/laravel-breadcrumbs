<?php

namespace ActTraining\LaravelBreadcrumbs\Tests\Unit;

use ActTraining\LaravelBreadcrumbs\BreadcrumbGenerator;
use ActTraining\LaravelBreadcrumbs\Breadcrumbs;
use ActTraining\LaravelBreadcrumbs\Tests\TestCase;
use ReflectionClass;

class BreadcrumbsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Clear any existing breadcrumb definitions before each test
        $reflection = new ReflectionClass(Breadcrumbs::class);
        $property = $reflection->getProperty('breadcrumbs');
        $property->setAccessible(true);
        $property->setValue(null, []);
    }

    /** @test */
    public function it_can_define_and_generate_simple_breadcrumbs()
    {
        Breadcrumbs::define('dashboard', function ($trail) {
            $trail->push('Dashboard', '/dashboard');
        });

        $breadcrumbs = Breadcrumbs::generate('dashboard');

        $this->assertCount(1, $breadcrumbs);
        $this->assertEquals('Dashboard', $breadcrumbs[0]->title);
        $this->assertEquals('/dashboard', $breadcrumbs[0]->url);
    }

    /** @test */
    public function it_can_generate_breadcrumbs_with_parent_relationships()
    {
        Breadcrumbs::define('dashboard', function ($trail) {
            $trail->push('Dashboard', '/dashboard');
        });

        Breadcrumbs::define('users.index', function ($trail) {
            $trail->parent('dashboard');
            $trail->push('Users', '/users');
        });

        $breadcrumbs = Breadcrumbs::generate('users.index');

        $this->assertCount(2, $breadcrumbs);
        $this->assertEquals('Dashboard', $breadcrumbs[0]->title);
        $this->assertEquals('/dashboard', $breadcrumbs[0]->url);
        $this->assertEquals('Users', $breadcrumbs[1]->title);
        $this->assertEquals('/users', $breadcrumbs[1]->url);
    }

    /** @test */
    public function it_can_generate_breadcrumbs_with_parameters()
    {
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

        $this->assertCount(3, $breadcrumbs);
        $this->assertEquals('Dashboard', $breadcrumbs[0]->title);
        $this->assertEquals('Users', $breadcrumbs[1]->title);
        $this->assertEquals('John Doe', $breadcrumbs[2]->title);
        $this->assertEquals('/users/1', $breadcrumbs[2]->url);
    }

    /** @test */
    public function it_can_generate_breadcrumbs_with_null_urls()
    {
        Breadcrumbs::define('dashboard', function ($trail) {
            $trail->push('Dashboard', '/dashboard');
        });

        Breadcrumbs::define('users.create', function ($trail) {
            $trail->parent('dashboard');
            $trail->push('Create User', null);
        });

        $breadcrumbs = Breadcrumbs::generate('users.create');

        $this->assertCount(2, $breadcrumbs);
        $this->assertEquals('Dashboard', $breadcrumbs[0]->title);
        $this->assertEquals('/dashboard', $breadcrumbs[0]->url);
        $this->assertEquals('Create User', $breadcrumbs[1]->title);
        $this->assertNull($breadcrumbs[1]->url);
    }

    /** @test */
    public function it_returns_empty_array_for_undefined_routes()
    {
        $breadcrumbs = Breadcrumbs::generate('undefined.route');

        $this->assertEmpty($breadcrumbs);
    }

    /** @test */
    public function it_can_check_if_breadcrumb_exists()
    {
        Breadcrumbs::define('test.route', function ($trail) {
            $trail->push('Test', '/test');
        });

        $this->assertTrue(Breadcrumbs::exists('test.route'));
        $this->assertFalse(Breadcrumbs::exists('nonexistent.route'));
    }

    /** @test */
    public function it_can_forget_breadcrumb_definitions()
    {
        Breadcrumbs::define('test.route', function ($trail) {
            $trail->push('Test', '/test');
        });

        $this->assertTrue(Breadcrumbs::exists('test.route'));

        Breadcrumbs::forget('test.route');

        $this->assertFalse(Breadcrumbs::exists('test.route'));
    }

    /** @test */
    public function it_can_clear_all_breadcrumb_definitions()
    {
        Breadcrumbs::define('test1', function ($trail) {
            $trail->push('Test 1', '/test1');
        });

        Breadcrumbs::define('test2', function ($trail) {
            $trail->push('Test 2', '/test2');
        });

        $this->assertTrue(Breadcrumbs::exists('test1'));
        $this->assertTrue(Breadcrumbs::exists('test2'));

        Breadcrumbs::clear();

        $this->assertFalse(Breadcrumbs::exists('test1'));
        $this->assertFalse(Breadcrumbs::exists('test2'));
    }

    /** @test */
    public function it_can_get_all_breadcrumb_definitions()
    {
        Breadcrumbs::define('test1', function ($trail) {
            $trail->push('Test 1', '/test1');
        });

        Breadcrumbs::define('test2', function ($trail) {
            $trail->push('Test 2', '/test2');
        });

        $all = Breadcrumbs::all();

        $this->assertArrayHasKey('test1', $all);
        $this->assertArrayHasKey('test2', $all);
        $this->assertCount(2, $all);
    }
}
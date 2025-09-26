<?php

namespace ActTraining\LaravelBreadcrumbs\Tests\Feature;

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;
use ActTraining\LaravelBreadcrumbs\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

class BreadcrumbsComponentTest extends TestCase
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
    public function it_renders_breadcrumbs_component()
    {
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
        $view->assertSee('breadcrumbs');
    }

    /** @test */
    public function it_generates_breadcrumbs_from_current_route()
    {
        Route::get('/test', function () {
            return 'test';
        })->name('test.route');

        Breadcrumbs::define('test.route', function ($trail) {
            $trail->push('Test Page', '/test');
        });

        $this->get('/test');

        $view = $this->blade('<x-breadcrumbs />');

        $view->assertSee('Test Page');
    }

    /** @test */
    public function it_skips_single_item_breadcrumbs_by_default()
    {
        Breadcrumbs::define('single', function ($trail) {
            $trail->push('Single Item', '/single');
        });

        $view = $this->blade('<x-breadcrumbs route="single" />');

        $view->assertDontSee('Single Item');
    }

    /** @test */
    public function it_can_show_single_item_breadcrumbs_when_configured()
    {
        config(['breadcrumbs.skip_single_item' => false]);

        Breadcrumbs::define('single', function ($trail) {
            $trail->push('Single Item', '/single');
        });

        $view = $this->blade('<x-breadcrumbs route="single" />');

        $view->assertSee('Single Item');
    }

    /** @test */
    public function it_applies_custom_css_classes()
    {
        config([
            'breadcrumbs.classes.wrapper' => 'custom-breadcrumbs',
            'breadcrumbs.skip_single_item' => false,
        ]);

        Breadcrumbs::define('test', function ($trail) {
            $trail->push('Test', '/test');
        });

        $view = $this->blade('<x-breadcrumbs route="test" />');

        $view->assertSee('custom-breadcrumbs');
    }

    /** @test */
    public function it_uses_custom_separator()
    {
        config([
            'breadcrumbs.separator' => '>',
            'breadcrumbs.skip_single_item' => false,
        ]);

        Breadcrumbs::define('parent', function ($trail) {
            $trail->push('Parent', '/parent');
        });

        Breadcrumbs::define('child', function ($trail) {
            $trail->parent('parent');
            $trail->push('Child', '/child');
        });

        $view = $this->blade('<x-breadcrumbs route="child" />');

        $view->assertSee('>');
    }
}
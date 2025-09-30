<?php

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;
use ActTraining\LaravelBreadcrumbs\BreadcrumbsServiceProvider;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Clear any existing breadcrumb definitions before each test
    $reflection = new ReflectionClass(Breadcrumbs::class);
    $property = $reflection->getProperty('breadcrumbs');
    $property->setValue(null, []);
});

it('loads breadcrumb definitions from default file when config is null', function () {
    // Clear existing breadcrumbs
    Breadcrumbs::clear();

    // Create a temporary breadcrumbs file
    $breadcrumbsPath = base_path('routes/breadcrumbs.php');
    $breadcrumbsDir = dirname($breadcrumbsPath);

    if (!File::exists($breadcrumbsDir)) {
        File::makeDirectory($breadcrumbsDir, 0755, true);
    }

    File::put($breadcrumbsPath, <<<'PHP'
<?php

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;

Breadcrumbs::define('test.null', function ($trail) {
    $trail->push('Test Null', '/test-null');
});
PHP
    );

    try {
        // Set config to null and manually call loadBreadcrumbDefinitions
        config(['breadcrumbs.definitions_file' => null]);

        // Use reflection to call the protected method
        $provider = new BreadcrumbsServiceProvider($this->app);
        $reflection = new ReflectionClass($provider);
        $method = $reflection->getMethod('loadBreadcrumbDefinitions');
        $method->invoke($provider);

        // Verify breadcrumb was loaded
        expect(Breadcrumbs::exists('test.null'))->toBeTrue();
    } finally {
        // Cleanup
        if (File::exists($breadcrumbsPath)) {
            File::delete($breadcrumbsPath);
        }
    }
});

it('does not load any file when definitions_file is set to false', function () {
    // Clear existing breadcrumbs
    Breadcrumbs::clear();

    // Create a temporary breadcrumbs file that should NOT be loaded
    $breadcrumbsPath = base_path('routes/breadcrumbs.php');
    $breadcrumbsDir = dirname($breadcrumbsPath);

    if (!File::exists($breadcrumbsDir)) {
        File::makeDirectory($breadcrumbsDir, 0755, true);
    }

    File::put($breadcrumbsPath, <<<'PHP'
<?php

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;

Breadcrumbs::define('should.not.load', function ($trail) {
    $trail->push('Should Not Load', '/should-not-load');
});
PHP
    );

    try {
        // Set config to false
        config(['breadcrumbs.definitions_file' => false]);

        // Use reflection to call the protected method
        $provider = new BreadcrumbsServiceProvider($this->app);
        $reflection = new ReflectionClass($provider);
        $method = $reflection->getMethod('loadBreadcrumbDefinitions');
        $method->invoke($provider);

        // Verify breadcrumb was NOT loaded
        expect(Breadcrumbs::exists('should.not.load'))->toBeFalse();
    } finally {
        // Cleanup
        if (File::exists($breadcrumbsPath)) {
            File::delete($breadcrumbsPath);
        }
    }
});

it('loads breadcrumb definitions from custom file path', function () {
    // Clear existing breadcrumbs
    Breadcrumbs::clear();

    // Create a custom breadcrumbs file
    $customPath = base_path('config/custom-breadcrumbs.php');

    File::put($customPath, <<<'PHP'
<?php

use ActTraining\LaravelBreadcrumbs\Breadcrumbs;

Breadcrumbs::define('custom.route', function ($trail) {
    $trail->push('Custom Route', '/custom');
});
PHP
    );

    try {
        // Set config to custom path
        config(['breadcrumbs.definitions_file' => $customPath]);

        // Use reflection to call the protected method
        $provider = new BreadcrumbsServiceProvider($this->app);
        $reflection = new ReflectionClass($provider);
        $method = $reflection->getMethod('loadBreadcrumbDefinitions');
        $method->invoke($provider);

        // Verify breadcrumb was loaded
        expect(Breadcrumbs::exists('custom.route'))->toBeTrue();
    } finally {
        // Cleanup
        if (File::exists($customPath)) {
            File::delete($customPath);
        }
    }
});

it('does not error when definitions_file path does not exist', function () {
    // Set config to a non-existent path
    config(['breadcrumbs.definitions_file' => base_path('does/not/exist.php')]);

    // Use reflection to call the protected method
    $provider = new BreadcrumbsServiceProvider($this->app);
    $reflection = new ReflectionClass($provider);
    $method = $reflection->getMethod('loadBreadcrumbDefinitions');

    // Should not throw an exception
    expect(fn() => $method->invoke($provider))->not->toThrow(Exception::class);
});
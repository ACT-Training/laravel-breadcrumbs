<?php

namespace ActTraining\LaravelBreadcrumbs;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use ActTraining\LaravelBreadcrumbs\Console\Commands\MakeBreadcrumbCommand;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/breadcrumbs.php',
            'breadcrumbs'
        );

        $this->app->singleton(Breadcrumbs::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'breadcrumbs');

        $this->publishes([
            __DIR__ . '/../config/breadcrumbs.php' => config_path('breadcrumbs.php'),
        ], 'breadcrumbs-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/breadcrumbs'),
        ], 'breadcrumbs-views');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeBreadcrumbCommand::class,
            ]);
        }

        Blade::component('breadcrumbs', \ActTraining\LaravelBreadcrumbs\View\Components\Breadcrumbs::class);

        // Load breadcrumb definitions if the file exists
        $this->loadBreadcrumbDefinitions();
    }

    protected function loadBreadcrumbDefinitions(): void
    {
        $breadcrumbsFile = config('breadcrumbs.definitions_file');

        if ($breadcrumbsFile && file_exists($breadcrumbsFile)) {
            require_once $breadcrumbsFile;
        }

        // Fallback to default location
        $defaultPath = base_path('routes/breadcrumbs.php');
        if (file_exists($defaultPath) && $breadcrumbsFile !== $defaultPath) {
            require_once $defaultPath;
        }
    }
}
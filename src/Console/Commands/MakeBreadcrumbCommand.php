<?php

namespace ActTraining\LaravelBreadcrumbs\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeBreadcrumbCommand extends Command
{
    protected $signature = 'make:breadcrumb {name : The name of the breadcrumb route}
                                          {--parent= : The parent breadcrumb route}
                                          {--title= : The breadcrumb title}
                                          {--url= : The breadcrumb URL}';

    protected $description = 'Create a new breadcrumb definition';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $parent = $this->option('parent');
        $title = $this->option('title') ?? $this->ask('What should be the breadcrumb title?', str_replace('.', ' ', $name));
        $url = $this->option('url') ?? $this->ask('What should be the breadcrumb URL? (leave blank for route helper)', "route('{$name}')");

        $breadcrumbsFile = $this->getBreadcrumbsFile();
        $definition = $this->generateBreadcrumbDefinition($name, $parent, $title, $url);

        if (!$this->files->exists($breadcrumbsFile)) {
            $this->createBreadcrumbsFile($breadcrumbsFile);
        }

        $this->files->append($breadcrumbsFile, $definition);

        $this->components->info("Breadcrumb [{$name}] created successfully.");

        return self::SUCCESS;
    }

    protected function getBreadcrumbsFile(): string
    {
        $configFile = config('breadcrumbs.definitions_file');

        if ($configFile && $this->files->exists($configFile)) {
            return $configFile;
        }

        return base_path('routes/breadcrumbs.php');
    }

    protected function createBreadcrumbsFile(string $path): void
    {
        $directory = dirname($path);

        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }

        $stub = "<?php\n\nuse ActTraining\\LaravelBreadcrumbs\\Facades\\Breadcrumbs;\n\n";
        $this->files->put($path, $stub);

        $this->components->info("Created breadcrumbs file: {$path}");
    }

    protected function generateBreadcrumbDefinition(string $name, ?string $parent, string $title, string $url): string
    {
        $urlValue = $this->formatUrl($url);

        $definition = "\n// " . ucwords(str_replace(['.', '_'], ' ', $name)) . "\n";
        $definition .= "Breadcrumbs::define('{$name}', function (\$trail) {\n";

        if ($parent) {
            $definition .= "    \$trail->parent('{$parent}');\n";
        }

        $definition .= "    \$trail->push('{$title}', {$urlValue});\n";
        $definition .= "});\n";

        return $definition;
    }

    protected function formatUrl(string $url): string
    {
        if (empty($url)) {
            return 'null';
        }

        if (str_starts_with($url, 'route(') || str_starts_with($url, 'url(')) {
            return $url;
        }

        if (str_starts_with($url, '/') || str_starts_with($url, 'http')) {
            return "'{$url}'";
        }

        return "route('{$url}')";
    }
}
<?php

namespace ActTraining\LaravelBreadcrumbs;

class BreadcrumbGenerator
{
    protected array $breadcrumbs = [];

    public function push(string $title, ?string $url = null): void
    {
        $this->breadcrumbs[] = (object) [
            'title' => $title,
            'url' => $url,
        ];
    }

    public function parent(string $route, ...$parameters): void
    {
        $parentBreadcrumbs = Breadcrumbs::generate($route, ...$parameters);
        $this->breadcrumbs = array_merge($parentBreadcrumbs, $this->breadcrumbs);
    }

    public function getBreadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function count(): int
    {
        return count($this->breadcrumbs);
    }

    public function isEmpty(): bool
    {
        return empty($this->breadcrumbs);
    }

    public function clear(): void
    {
        $this->breadcrumbs = [];
    }
}
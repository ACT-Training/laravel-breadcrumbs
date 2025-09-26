<?php

namespace ActTraining\LaravelBreadcrumbs\View\Components;

use ActTraining\LaravelBreadcrumbs\Breadcrumbs as BreadcrumbsManager;
use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public array $breadcrumbs;
    public array $classes;
    public string $separator;
    public bool $skipSingleItem;

    public function __construct(
        ?string $route = null,
        array $params = [],
        ?string $view = null,
        array $classes = [],
        ?string $separator = null,
        ?bool $skipSingleItem = null
    ) {
        $this->breadcrumbs = $this->generateBreadcrumbs($route, $params);
        $this->classes = array_merge(config('breadcrumbs.classes', []), $classes);
        $this->separator = $separator ?? config('breadcrumbs.separator', '/');
        $this->skipSingleItem = $skipSingleItem ?? config('breadcrumbs.skip_single_item', true);
    }

    public function render()
    {
        if ($this->skipSingleItem && count($this->breadcrumbs) <= 1) {
            return '';
        }

        return view(config('breadcrumbs.view', 'breadcrumbs::breadcrumbs'));
    }

    public function shouldRender(): bool
    {
        if ($this->skipSingleItem && count($this->breadcrumbs) <= 1) {
            return false;
        }

        return !empty($this->breadcrumbs);
    }

    protected function generateBreadcrumbs(?string $route, array $params): array
    {
        if ($route) {
            return BreadcrumbsManager::generate($route, ...$params);
        }

        return BreadcrumbsManager::generateFromRoute();
    }
}
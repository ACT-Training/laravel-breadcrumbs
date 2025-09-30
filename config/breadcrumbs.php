<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Breadcrumb Definitions File
    |--------------------------------------------------------------------------
    |
    | This option controls the default location of your breadcrumb definitions
    | file. You can change this to any location within your application.
    | If null, the package will look for routes/breadcrumbs.php.
    | Set to false to disable loading a definition file entirely.
    |
    */

    'definitions_file' => base_path('routes/breadcrumbs.php'),

    /*
    |--------------------------------------------------------------------------
    | Default View
    |--------------------------------------------------------------------------
    |
    | This option controls the default view that will be used to render
    | breadcrumbs. You can change this to use your own custom view.
    |
    */

    'view' => 'breadcrumbs::breadcrumbs',

    /*
    |--------------------------------------------------------------------------
    | View Data
    |--------------------------------------------------------------------------
    |
    | Additional data that should be passed to the breadcrumb view.
    | This can be useful for customizing the appearance or behavior
    | of breadcrumbs across your application.
    |
    */

    'view_data' => [],

    /*
    |--------------------------------------------------------------------------
    | CSS Classes
    |--------------------------------------------------------------------------
    |
    | Default CSS classes that will be applied to breadcrumb elements.
    | You can customize these to match your application's design system.
    |
    */

    'classes' => [
        'wrapper' => 'breadcrumbs',
        'list' => 'breadcrumb-list',
        'item' => 'breadcrumb-item',
        'link' => 'breadcrumb-link',
        'active' => 'breadcrumb-active',
        'separator' => 'breadcrumb-separator',
    ],

    /*
    |--------------------------------------------------------------------------
    | Separator
    |--------------------------------------------------------------------------
    |
    | The separator character or HTML that will be displayed between
    | breadcrumb items. You can use text, HTML, or even Unicode symbols.
    |
    */

    'separator' => '/',

    /*
    |--------------------------------------------------------------------------
    | Skip Single Item
    |--------------------------------------------------------------------------
    |
    | When set to true, breadcrumbs with only one item will not be displayed.
    | This is useful if you don't want to show breadcrumbs on pages that
    | don't have any parent pages.
    |
    */

    'skip_single_item' => true,

    /*
    |--------------------------------------------------------------------------
    | Home Route
    |--------------------------------------------------------------------------
    |
    | The route name that represents the home/root page of your application.
    | This route will be displayed with special styling and optionally with
    | a house icon. Commonly set to 'dashboard', 'home', or 'index'.
    |
    */

    'home_route' => 'dashboard',

    /*
    |--------------------------------------------------------------------------
    | Home Display Style
    |--------------------------------------------------------------------------
    |
    | Controls how the home breadcrumb is displayed. Options:
    | - 'icon' : Shows only the house icon
    | - 'text' : Shows only the text label
    | - 'both' : Shows both icon and text label
    |
    */

    'home_display' => 'icon',

    /*
    |--------------------------------------------------------------------------
    | Home Icon
    |--------------------------------------------------------------------------
    |
    | The icon to use for the home breadcrumb. This should be a valid
    | FluxUI icon name. Common options: 'house', 'home', 'squares-2x2'
    |
    */

    'home_icon' => 'house',
];
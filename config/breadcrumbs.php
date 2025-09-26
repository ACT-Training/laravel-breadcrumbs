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
];
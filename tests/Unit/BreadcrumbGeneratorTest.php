<?php

use ActTraining\LaravelBreadcrumbs\BreadcrumbGenerator;

it('can push breadcrumbs', function () {
    $generator = new BreadcrumbGenerator;

    $generator->push('Home', '/home');
    $generator->push('About', '/about');

    $breadcrumbs = $generator->getBreadcrumbs();

    expect($breadcrumbs)->toHaveCount(2);
    expect($breadcrumbs[0]->title)->toBe('Home');
    expect($breadcrumbs[0]->url)->toBe('/home');
    expect($breadcrumbs[1]->title)->toBe('About');
    expect($breadcrumbs[1]->url)->toBe('/about');
});

it('can push breadcrumbs without urls', function () {
    $generator = new BreadcrumbGenerator;

    $generator->push('Current Page', null);

    $breadcrumbs = $generator->getBreadcrumbs();

    expect($breadcrumbs)->toHaveCount(1);
    expect($breadcrumbs[0]->title)->toBe('Current Page');
    expect($breadcrumbs[0]->url)->toBeNull();
});

it('can count breadcrumbs', function () {
    $generator = new BreadcrumbGenerator;

    expect($generator->count())->toBe(0);
    expect($generator->isEmpty())->toBeTrue();

    $generator->push('Test', '/test');

    expect($generator->count())->toBe(1);
    expect($generator->isEmpty())->toBeFalse();
});

it('can clear breadcrumbs', function () {
    $generator = new BreadcrumbGenerator;

    $generator->push('Test', '/test');
    expect($generator->count())->toBe(1);

    $generator->clear();
    expect($generator->count())->toBe(0);
    expect($generator->isEmpty())->toBeTrue();
});
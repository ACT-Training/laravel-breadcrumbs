<?php

use ActTraining\LaravelBreadcrumbs\BreadcrumbGenerator;

it('can push breadcrumbs', function () {
    $generator = new BreadcrumbGenerator;

    $generator->push('Home', '/home');
    $generator->push('About', '/about');

    $breadcrumbs = $generator->getBreadcrumbs();

    expect($breadcrumbs)->toHaveCount(2)
        ->and($breadcrumbs[0]->title)->toBe('Home')
        ->and($breadcrumbs[0]->url)->toBe('/home')
        ->and($breadcrumbs[1]->title)->toBe('About')
        ->and($breadcrumbs[1]->url)->toBe('/about');
});

it('can push breadcrumbs without urls', function () {
    $generator = new BreadcrumbGenerator;

    $generator->push('Current Page', null);

    $breadcrumbs = $generator->getBreadcrumbs();

    expect($breadcrumbs)->toHaveCount(1)
        ->and($breadcrumbs[0]->title)->toBe('Current Page')
        ->and($breadcrumbs[0]->url)->toBeNull();
});

it('can count breadcrumbs', function () {
    $generator = new BreadcrumbGenerator;

    expect($generator->count())->toBe(0)
        ->and($generator->isEmpty())->toBeTrue();

    $generator->push('Test', '/test');

    expect($generator->count())->toBe(1)
        ->and($generator->isEmpty())->toBeFalse();
});

it('can clear breadcrumbs', function () {
    $generator = new BreadcrumbGenerator;

    $generator->push('Test', '/test');
    expect($generator->count())->toBe(1);

    $generator->clear();
    expect($generator->count())->toBe(0)
        ->and($generator->isEmpty())->toBeTrue();
});
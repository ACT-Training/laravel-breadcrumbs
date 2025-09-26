<?php

namespace ActTraining\LaravelBreadcrumbs\Tests\Unit;

use ActTraining\LaravelBreadcrumbs\BreadcrumbGenerator;
use ActTraining\LaravelBreadcrumbs\Tests\TestCase;

class BreadcrumbGeneratorTest extends TestCase
{
    /** @test */
    public function it_can_push_breadcrumbs()
    {
        $generator = new BreadcrumbGenerator;

        $generator->push('Home', '/home');
        $generator->push('About', '/about');

        $breadcrumbs = $generator->getBreadcrumbs();

        $this->assertCount(2, $breadcrumbs);
        $this->assertEquals('Home', $breadcrumbs[0]->title);
        $this->assertEquals('/home', $breadcrumbs[0]->url);
        $this->assertEquals('About', $breadcrumbs[1]->title);
        $this->assertEquals('/about', $breadcrumbs[1]->url);
    }

    /** @test */
    public function it_can_push_breadcrumbs_without_urls()
    {
        $generator = new BreadcrumbGenerator;

        $generator->push('Current Page', null);

        $breadcrumbs = $generator->getBreadcrumbs();

        $this->assertCount(1, $breadcrumbs);
        $this->assertEquals('Current Page', $breadcrumbs[0]->title);
        $this->assertNull($breadcrumbs[0]->url);
    }

    /** @test */
    public function it_can_count_breadcrumbs()
    {
        $generator = new BreadcrumbGenerator;

        $this->assertEquals(0, $generator->count());
        $this->assertTrue($generator->isEmpty());

        $generator->push('Test', '/test');

        $this->assertEquals(1, $generator->count());
        $this->assertFalse($generator->isEmpty());
    }

    /** @test */
    public function it_can_clear_breadcrumbs()
    {
        $generator = new BreadcrumbGenerator;

        $generator->push('Test', '/test');
        $this->assertEquals(1, $generator->count());

        $generator->clear();
        $this->assertEquals(0, $generator->count());
        $this->assertTrue($generator->isEmpty());
    }
}
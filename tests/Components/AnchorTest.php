<?php

namespace ClaudioDekker\ChangelogUpdater\Tests\Components;

use ClaudioDekker\ChangelogUpdater\Components\Anchor;
use PHPUnit\Framework\TestCase;

class AnchorTest extends TestCase
{
    /** @test */
    public function it_parses_github_pull_requests(): void
    {
        $anchor = Anchor::make('https://github.com/laravel/framework/pull/46137');

        $this->assertSame(
            '[#46137](https://github.com/laravel/framework/pull/46137)',
            (string) $anchor
        );
    }

    /** @test */
    public function it_parses_github_issues(): void
    {
        $anchor = Anchor::make('https://github.com/laravel/framework/issues/117');

        $this->assertSame(
            '[#117](https://github.com/laravel/framework/issues/117)',
            (string) $anchor
        );
    }

    /** @test */
    public function it_trims_https_from_the_beginning_of_urls(): void
    {
        $anchor = Anchor::make('https://google.com/laravel/framework/pull/46137');

        $this->assertSame(
            '[google.com/laravel/framework/pull/46137](https://google.com/laravel/framework/pull/46137)',
            (string) $anchor
        );
    }

    /** @test */
    public function it_trims_http_from_the_beginning_of_urls(): void
    {
        $anchor = Anchor::make('http://google.com/laravel/framework/pull/46137');

        $this->assertSame(
            '[google.com/laravel/framework/pull/46137](http://google.com/laravel/framework/pull/46137)',
            (string) $anchor
        );
    }

    /** @test */
    public function it_trims_www_from_the_beginning_of_urls(): void
    {
        $anchorA = Anchor::make('https://www.google.com/laravel/framework/pull/46137');
        $anchorB = Anchor::make('http://www.google.com/laravel/framework/pull/46137');
        $anchorC = Anchor::make('www.google.com/laravel/framework/pull/46137');

        $this->assertSame(
            '[google.com/laravel/framework/pull/46137](https://www.google.com/laravel/framework/pull/46137)',
            (string) $anchorA
        );
        $this->assertSame(
            '[google.com/laravel/framework/pull/46137](http://www.google.com/laravel/framework/pull/46137)',
            (string) $anchorB
        );
        $this->assertSame(
            '[google.com/laravel/framework/pull/46137](www.google.com/laravel/framework/pull/46137)',
            (string) $anchorC
        );
    }

    /** @test */
    public function the_label_can_be_customized(): void
    {
        $anchorA = Anchor::make('https://github.com/laravel/framework/compare/v10.6.0...v10.6.1', 'Compare');

        $anchorB = Anchor::make('https://github.com/laravel/framework/pull/46137');
        $anchorB->label('Custom label');

        $this->assertSame(
            '[Compare](https://github.com/laravel/framework/compare/v10.6.0...v10.6.1)',
            (string) $anchorA
        );
        $this->assertSame(
            '[Custom label](https://github.com/laravel/framework/pull/46137)',
            (string) $anchorB
        );
    }

    /** @test */
    public function it_becomes_an_empty_string_when_the_href_is_empty(): void
    {
        $this->assertSame('', (string) Anchor::make());
        $this->assertSame('', (string) Anchor::make(''));
        $this->assertSame('', (string) Anchor::make('', ''));
        $this->assertSame('', (string) Anchor::make('')->label('foo'));
    }
}

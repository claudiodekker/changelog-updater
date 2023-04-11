<?php

namespace ClaudioDekker\ChangelogUpdater\Tests\Components;

use ClaudioDekker\ChangelogUpdater\Components\Anchor;
use ClaudioDekker\ChangelogUpdater\Components\Entry;
use PHPUnit\Framework\TestCase;

class EntryTest extends TestCase
{
    /** @test */
    public function it_formats_the_entry(): void
    {
        $entry = Entry::make(
            'Fix Expression string casting',
            'https://github.com/laravel/framework/pull/46137',
        );

        $this->assertSame(
            '- Fix Expression string casting ([#46137](https://github.com/laravel/framework/pull/46137))',
            (string) $entry
        );
    }

    /** @test */
    public function it_accepts_anchor_instances(): void
    {
        $entry = Entry::make(
            'Fix Expression string casting',
            Anchor::make('https://github.com/laravel/framework/pull/46137', 'Foo'),
        );

        $this->assertSame(
            '- Fix Expression string casting ([Foo](https://github.com/laravel/framework/pull/46137))',
            (string) $entry
        );
    }

    /** @test */
    public function the_url_is_optional(): void
    {
        $entry = Entry::make('Fix Expression string casting');

        $this->assertSame(
            '- Fix Expression string casting',
            (string) $entry
        );
    }

    /** @test */
    public function it_trims_extra_spaces_from_the_title(): void
    {
        $entryA = Entry::make('Fix Expression string casting  ');
        $entryB = Entry::make('  Fix Expression string casting       ', 'https://laravel.com');

        $this->assertSame('- Fix Expression string casting', (string) $entryA);
        $this->assertSame('- Fix Expression string casting ([laravel.com](https://laravel.com))', (string) $entryB);
    }
}

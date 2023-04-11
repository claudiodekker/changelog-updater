<?php

namespace ClaudioDekker\ChangelogUpdater\Tests\Components;

use ClaudioDekker\ChangelogUpdater\Components\Entry;
use ClaudioDekker\ChangelogUpdater\Components\Section;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    /** @test */
    public function it_formats_the_section(): void
    {
        $section = Section::make('Added')
            ->addEntry(Entry::make('Add support for PHP 8.1', 'https://github.com/laravel/framework/pull/1234'))
            ->addEntry(Entry::make('Register policies automatically to the gate', 'https://github.com/laravel/framework/pull/46132'));

        $this->assertSame(
            "### Added\n".
            "\n".
            "- Add support for PHP 8.1 ([#1234](https://github.com/laravel/framework/pull/1234))\n".
            "- Register policies automatically to the gate ([#46132](https://github.com/laravel/framework/pull/46132))\n",
            (string) $section
        );
    }

    /** @test */
    public function it_becomes_an_empty_string_when_there_are_no_entries(): void
    {
        $section = Section::make('Added');

        $this->assertSame('', (string) $section);
    }
}

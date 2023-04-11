<?php

namespace ClaudioDekker\ChangelogUpdater\Tests\Components;

use ClaudioDekker\ChangelogUpdater\Components\Anchor;
use ClaudioDekker\ChangelogUpdater\Components\Entry;
use ClaudioDekker\ChangelogUpdater\Components\Release;
use ClaudioDekker\ChangelogUpdater\Components\Section;
use PHPUnit\Framework\TestCase;

class ReleaseTest extends TestCase
{
    /** @test */
    public function it_formats_the_release(): void
    {
        $release = Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.0...v10.0.1', 'v10.0.1 (2023-02-16)'))
            ->addSection(Section::make('Added')
                ->addEntry(Entry::make('Standard Input can be applied to PendingProcess', 'https://github.com/laravel/framework/pull/46119'))
            )
            ->addSection(Section::make('Fixed')
                ->addEntry(Entry::make('Fix Expression string casting', 'https://github.com/laravel/framework/pull/46137'))
            )
            ->addSection(Section::make('Changed')
                ->addEntry(Entry::make('Add AddQueuedCookiesToResponse to middlewarePriority so it is handled in the right place', 'https://github.com/laravel/framework/pull/46130'))
                ->addEntry(Entry::make('Show queue connection in MonitorCommand', 'https://github.com/laravel/framework/pull/46122'))
            );

        $this->assertSame(
            "## [v10.0.1 (2023-02-16)](https://github.com/laravel/framework/compare/v10.0.0...v10.0.1)\n".
            "\n".
            "### Added\n".
            "\n".
            "- Standard Input can be applied to PendingProcess ([#46119](https://github.com/laravel/framework/pull/46119))\n".
            "\n".
            "### Fixed\n".
            "\n".
            "- Fix Expression string casting ([#46137](https://github.com/laravel/framework/pull/46137))\n".
            "\n".
            "### Changed\n".
            "\n".
            "- Add AddQueuedCookiesToResponse to middlewarePriority so it is handled in the right place ([#46130](https://github.com/laravel/framework/pull/46130))\n".
            "- Show queue connection in MonitorCommand ([#46122](https://github.com/laravel/framework/pull/46122))\n".
            "\n",
            (string) $release
        );
    }

    /** @test */
    public function it_formats_the_release_as_per_keepachangelog_order_and_adds_custom_types_to_the_end_in_alphabetical_order(): void
    {
        $release = Release::make('Keep a changelog '.Anchor::make('https://keepachangelog.com/en/1.1.0/', 'v1.1.0'))
            ->addSection(Section::make('A Custom Section')->addEntry('This is after Security'))
            ->addSection(Section::make('Removed')->addEntry('for now removed features'))
            ->addSection(Section::make('Deprecated')->addEntry('for soon-to-be removed features'))
            ->addSection(Section::make('Fixed')->addEntry('for any bug fixes'))
            ->addSection(Section::make('Security')->addEntry('in case of vulnerabilities'))
            ->addSection(Section::make('Changed')->addEntry('for changes in existing functionality'))
            ->addSection(Section::make('Zonda')->addEntry('This is after Everything Else'))
            ->addSection(Section::make('Everything Else')->addEntry('This is after A Custom Section'))
            ->addSection(Section::make('Added')->addEntry('for new features'));

        $this->assertSame(
            "## Keep a changelog [v1.1.0](https://keepachangelog.com/en/1.1.0/)\n".
            "\n".
            "### Added\n".
            "\n".
            "- for new features\n".
            "\n".
            "### Fixed\n".
            "\n".
            "- for any bug fixes\n".
            "\n".
            "### Changed\n".
            "\n".
            "- for changes in existing functionality\n".
            "\n".
            "### Deprecated\n".
            "\n".
            "- for soon-to-be removed features\n".
            "\n".
            "### Removed\n".
            "\n".
            "- for now removed features\n".
            "\n".
            "### Security\n".
            "\n".
            "- in case of vulnerabilities\n".
            "\n".
            "### A Custom Section\n".
            "\n".
            "- This is after Security\n".
            "\n".
            "### Everything Else\n".
            "\n".
            "- This is after A Custom Section\n".
            "\n".
            "### Zonda\n".
            "\n".
            "- This is after Everything Else\n".
            "\n",
            (string) $release
        );
    }

    /** @test */
    public function it_can_have_an_optional_description(): void
    {
        $release = Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.0...v10.0.1', 'v10.0.1 (2023-02-16)'))
            ->setDescription('This release has some wonderful things.')
            ->addSection(Section::make('Added')->addEntry('Something new'));

        $this->assertSame(
            "## [v10.0.1 (2023-02-16)](https://github.com/laravel/framework/compare/v10.0.0...v10.0.1)\n".
            "\n".
            "This release has some wonderful things.\n".
            "\n".
            "### Added\n".
            "\n".
            "- Something new\n".
            "\n",
            (string) $release
        );
    }

    /** @test */
    public function it_can_only_have_a_description(): void
    {
        $release = Release::make('Some Release')
            ->setDescription('This release has some wonderful things.');

        $this->assertSame(
            "## Some Release\n".
            "\n".
            "This release has some wonderful things.\n".
            "\n",
            (string) $release
        );
    }

    /** @test */
    public function it_can_only_have_a_title(): void
    {
        $release = Release::make('Unreleased');

        $this->assertSame(
            "## Unreleased\n",
            (string) $release
        );
    }

    /** @test */
    public function it_can_find_a_section(): void
    {
        $release = Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.0...v10.0.1', 'v10.0.1 (2023-02-16)'))
            ->addSection(Section::make('Added')
                ->addEntry(Entry::make('Standard Input can be applied to PendingProcess', 'https://github.com/laravel/framework/pull/46119'))
            )
            ->addSection(Section::make('Fixed')
                ->addEntry(Entry::make('Fix Expression string casting', 'https://github.com/laravel/framework/pull/46137'))
            )
            ->addSection(Section::make('Changed')
                ->addEntry(Entry::make('Add AddQueuedCookiesToResponse to middlewarePriority so it is handled in the right place', 'https://github.com/laravel/framework/pull/46130'))
                ->addEntry(Entry::make('Show queue connection in MonitorCommand', 'https://github.com/laravel/framework/pull/46122'))
            );

        $fixed = "### Fixed\n\n- Fix Expression string casting ([#46137](https://github.com/laravel/framework/pull/46137))\n";

        $this->assertSame($fixed, (string) $release->section('fixed'));
        $this->assertSame($fixed, (string) $release->section('Fixed'));
    }

    /** @test */
    public function it_adds_the_section_when_it_could_not_be_found(): void
    {
        $release = Release::make('1.0.0');

        $this->assertSame('Fixed', $release->section('fixed')->title);
    }
}

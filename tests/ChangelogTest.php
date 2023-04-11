<?php

namespace ClaudioDekker\ChangelogUpdater\Tests;

use ClaudioDekker\ChangelogUpdater\Changelog;
use ClaudioDekker\ChangelogUpdater\ChangelogParser;
use ClaudioDekker\ChangelogUpdater\Components\Anchor;
use ClaudioDekker\ChangelogUpdater\Components\Entry;
use ClaudioDekker\ChangelogUpdater\Components\Release;
use ClaudioDekker\ChangelogUpdater\Components\Section;
use ClaudioDekker\ChangelogUpdater\Exceptions\ReleaseNotFoundException;
use PharIo\Version\InvalidVersionException;

class ChangelogTest extends TestCase
{
    /** @test */
    public function it_formats_the_release(): void
    {
        $changelog = Changelog::make('Release Notes for 10.x', '')
            ->setUnreleased(Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.3...10.x', 'Unreleased')))
            ->addRelease(Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.2...v10.0.3', 'v10.0.3 (2023-02-17)'))
                ->addSection(Section::make('Added')
                    ->addEntry(Entry::make('Added missing expression support for pluck in Builder', 'https://github.com/laravel/framework/pull/46146'))
                )
            )
            ->addRelease(Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.1...v10.0.2', 'v10.0.2 (2023-02-16)'))
                ->addSection(Section::make('Added')
                    ->addEntry(Entry::make('Register policies automatically to the gate', 'https://github.com/laravel/framework/pull/46132'))
                )
            )
            ->addRelease(Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.0...v10.0.1', 'v10.0.1 (2023-02-16)'))
                ->addSection(Section::make('Added')
                    ->addEntry(Entry::make('Standard Input can be applied to PendingProcess', 'https://github.com/laravel/framework/pull/46119'))
                )
                ->addSection(Section::make('Fixed')
                    ->addEntry(Entry::make('Fix Expression string casting', 'https://github.com/laravel/framework/pull/46137'))
                )
                ->addSection(Section::make('Changed')
                    ->addEntry(Entry::make('Add AddQueuedCookiesToResponse to middlewarePriority so it is handled in the right place', 'https://github.com/laravel/framework/pull/46130'))
                    ->addEntry(Entry::make('Show queue connection in MonitorCommand', 'https://github.com/laravel/framework/pull/46122'))
                )
            )
            ->addRelease(Release::make(Anchor::make('https://github.com/laravel/framework/compare/v10.0.0...10.x', 'v10.0.0 (2023-02-14)'))
                ->setDescription('Please consult the '.Anchor::make('https://laravel.com/docs/10.x/upgrade', 'upgrade guide').' and '.Anchor::make('https://laravel.com/docs/10.x/releases', 'release notes').' in the official Laravel documentation.')
            );

        $this->assertSame(
            $this->loadFixture('laravel-framework-10.md'),
            (string) $changelog
        );
    }

    /** @test */
    public function it_uses_the_default_keepachangelog_title_and_description(): void
    {
        $changelog = Changelog::make();

        $this->assertSame(
            $this->loadFixture('empty-changelog-default-title-default-description-unreleased-header.md'),
            (string) $changelog
        );
    }

    /** @test */
    public function the_changelog_title_can_be_customized()
    {
        $changelog = Changelog::make('Custom Title');

        $this->assertSame(
            $this->loadFixture('empty-changelog-custom-title-default-description-unreleased-header.md'),
            (string) $changelog
        );
    }

    /** @test */
    public function the_changelog_description_can_be_customized(): void
    {
        $changelog = Changelog::make(null, 'Custom Description');

        $this->assertSame(
            $this->loadFixture('empty-changelog-default-title-custom-description-unreleased-header.md'),
            (string) $changelog
        );
    }

    /** @test */
    public function it_can_find_a_release_from_the_releases(): void
    {
        $changelog = ChangelogParser::parse($this->loadFixture('laravel-framework-10-long.md'));

        $release1015 = "## [v10.5.0 (2023-03-28)](https://github.com/laravel/framework/compare/v10.4.1...v10.5.0)\n".
            "\n".
            "### Added\n".
            "\n".
            "- Added `Illuminate/Cache/CacheManager::setApplication()` ([#46594](https://github.com/laravel/framework/pull/46594))\n".
            "\n".
            "### Fixed\n".
            "\n".
            "- Fix infinite loading on batches list on Horizon ([#46536](https://github.com/laravel/framework/pull/46536))\n".
            "- Fix whereNull queries with raw expressions for the MySql grammar ([#46538](https://github.com/laravel/framework/pull/46538))\n".
            "- Fix getDirty method when using AsEnumArrayObject / AsEnumCollection ([#46561](https://github.com/laravel/framework/pull/46561))\n".
            "\n".
            "### Changed\n".
            "\n".
            "- Skip `Illuminate/Support/Reflector::isParameterBackedEnumWithStringBackingType` for non ReflectionNamedType ([#46511](https://github.com/laravel/framework/pull/46511))\n".
            "- Replace Deprecated DBAL Comparator creation with schema aware Comparator ([#46517](https://github.com/laravel/framework/pull/46517))\n".
            "- Added Storage::json() method to read and decode a json file ([#46548](https://github.com/laravel/framework/pull/46548))\n".
            "- Force cast json decoded failed_job_ids to array in DatabaseBatchRepository ([#46581](https://github.com/laravel/framework/pull/46581))\n".
            "- Handle empty arrays for DynamoDbStore multi-key operations ([#46579](https://github.com/laravel/framework/pull/46579))\n".
            "- Stop adding constraints twice on *Many to *One relationships via one() ([#46575](https://github.com/laravel/framework/pull/46575))\n".
            "- allow override of the Builder paginate() total ([#46415](https://github.com/laravel/framework/pull/46415))\n".
            "- Add a possibility to set a custom on_stats function for the Http Facade ([#46569](https://github.com/laravel/framework/pull/46569))\n".
            "\n";

        $this->assertSame($release1015, (string) $changelog->release('v10.5.0'));
        $this->assertSame($release1015, (string) $changelog->release('10.5'));
        $this->assertSame($release1015, (string) $changelog->release('v10.5'));
        $this->assertNotSame($release1015, (string) $changelog->release('10.5.1'));
        $this->assertNotSame($release1015, (string) $changelog->release('v10.5.1'));
    }

    /** @test */
    public function it_throws_an_exception_when_the_release_could_not_be_found(): void
    {
        $this->expectException(ReleaseNotFoundException::class);
        $this->expectExceptionMessage('Could not find release for version [10.99.9999].');

        $changelog = ChangelogParser::parse($this->loadFixture('laravel-framework-10-long.md'));

        $changelog->release('v10.99.9999');
    }

    /** @test */
    public function it_throws_an_exception_when_the_version_is_not_a_semver_version(): void
    {
        $this->expectException(InvalidVersionException::class);

        $changelog = ChangelogParser::parse($this->loadFixture('laravel-framework-10-long.md'));

        $changelog->release('v10.5.0.1');
    }

    /** @test */
    public function it_can_add_a_change_to_the_changelog(): void
    {
        $changelogA = ChangelogParser::parse($this->loadFixture('tailwindcss.md'));
        $changelogA->unreleased()->section('fixed')->addEntry(Entry::make('Fixed the whole front-end web-design ecosystem', 'https://tailwindcss.com'));

        $this->assertSame(
            $this->loadFixture('tailwindcss-with-changes.md'),
            (string) $changelogA
        );
    }
}

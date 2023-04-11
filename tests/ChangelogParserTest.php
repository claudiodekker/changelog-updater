<?php

namespace ClaudioDekker\ChangelogUpdater\Tests;

use ClaudioDekker\ChangelogUpdater\ChangelogParser;

class ChangelogParserTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider fixtures
     */
    public function it_parses_the_changelog(string $fixture): void
    {
        $changelog = $this->loadFixture($fixture);
        $parse = ChangelogParser::parse($changelog);

        $this->assertSame(
            $changelog,
            (string) $parse,
        );
    }

    protected function fixtures(): array
    {
        return [
            ['empty-changelog-default-title-default-description-unreleased-header.md'],
            ['empty-changelog-default-title-custom-description-unreleased-header.md'],
            ['empty-changelog-custom-title-default-description-unreleased-header.md'],
            ['laravel-framework-10.md'],
            ['tailwindcss.md'],
            ['inertia.md'],
        ];
    }
}

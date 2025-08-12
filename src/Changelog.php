<?php

namespace ClaudioDekker\ChangelogUpdater;

use ClaudioDekker\ChangelogUpdater\Components\Anchor;
use ClaudioDekker\ChangelogUpdater\Components\Release;
use ClaudioDekker\ChangelogUpdater\Exceptions\ReleaseNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PharIo\Version\Version;
use Stringable;

class Changelog implements Stringable
{
    protected string $title;

    protected string $description;

    protected Release $unreleased;

    protected Collection $releases;

    protected function __construct(?string $title = null, ?string $description = null)
    {
        $this->title = $title ?? 'Changelog';
        $this->description = $description ?? "All notable changes to this project will be documented in this file.\n\nThe format is based on ".Anchor::make('https://keepachangelog.com/en/1.0.0/', 'Keep a Changelog').",\nand this project adheres to ".Anchor::make('https://semver.org/spec/v2.0.0.html', 'Semantic Versioning').'.';
        $this->unreleased = Release::make('Unreleased');
        $this->releases = new Collection();
    }

    public static function make(?string $title = null, ?string $description = null): static
    {
        return new static($title, $description);
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setUnreleased(Release $release): self
    {
        $this->unreleased = $release;

        return $this;
    }

    public function addRelease(Release $release): self
    {
        $this->releases->push($release);

        return $this;
    }

    /**
     * @throws \PharIo\Version\InvalidVersionException
     * @throws \ClaudioDekker\ChangelogUpdater\Exceptions\ReleaseNotFoundException
     */
    public function release(string $search): Release
    {
        $version = (new Version($search))->getVersionString();

        $release = $this->releases->first(fn (Release $release) => Str::startsWith(Str::lower($release->title), [
            $version,
            "v$version",
            "[$version",
            "[v$version",
        ]));

        if (! $release) {
            throw new ReleaseNotFoundException("Could not find release for version [$version].");
        }

        return $release;
    }

    public function __toString()
    {
        $built = '# '.$this->title."\n\n";

        if ($this->description) {
            $built .= $this->description."\n\n";
        }

        $built .= rtrim($this->unreleased)."\n\n\n";

        if (! $this->releases->isEmpty()) {
            $built .= $this->releases
                ->map(fn (Release $release) => (string) $release)
                ->implode("\n");
        }

        return rtrim($built, "\n")."\n";
    }

    public function unreleased(): Release
    {
        return $this->unreleased;
    }
}

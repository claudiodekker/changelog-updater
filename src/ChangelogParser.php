<?php

namespace ClaudioDekker\ChangelogUpdater;

use ClaudioDekker\ChangelogUpdater\Components\Anchor;
use ClaudioDekker\ChangelogUpdater\Components\Entry;
use ClaudioDekker\ChangelogUpdater\Components\Release;
use ClaudioDekker\ChangelogUpdater\Components\Section;
use Illuminate\Support\Str;
use RuntimeException;

class ChangelogParser
{
    protected array $lines;

    public function __construct(string $content)
    {
        $this->lines = explode("\n", $content);
    }

    public static function parse(string $contents): Changelog
    {
        return (new static($contents))->process();
    }

    protected function extract(array &$buffer, string $until): array
    {
        $startOffset = null;

        foreach ($buffer as $key => $line) {
            if ($startOffset === null && empty(trim($line))) {
                continue;
            }

            if ($startOffset === null) {
                $startOffset = max(0, $key - 1);
            }

            if ($key > 0 && Str::startsWith($line, $until)) {
                return array_splice($buffer, $startOffset, $key);
            }
        }

        return array_splice($buffer, $startOffset ?? 0);
    }

    public function process(): Changelog
    {
        [$title, $description] = $this->parseChangelogHeader();

        $changelog = Changelog::make($title, $description);

        if ($unreleased = $this->parseRelease()) {
            Str::startsWith(Str::lower($unreleased->title), ['unreleased', '[unreleased'])
                ? $changelog->setUnreleased($unreleased)
                : $changelog->addRelease($unreleased);
        }

        while ($release = $this->parseRelease()) {
            $changelog->addRelease($release);
        }

        return $changelog;
    }

    protected function parseChangelogHeader(): array
    {
        if (! $chunk = $this->extract($this->lines, '## ')) {
            return [null, null];
        }

        $title = Str::after(array_shift($chunk), '# ');
        $description = $this->toParagraph($chunk);

        return [$title, $description];
    }

    protected function parseRelease(): ?Release
    {
        if (! $chunk = $this->extract($this->lines, '## ')) {
            return null;
        }

        if (! Str::startsWith($chunk[0], '## ')) {
            throw new RuntimeException('Invalid changelog format. Expected a release header, but got: '.$chunk[0]);
        }

        $release = Release::make(Str::after(array_shift($chunk), '## '));
        $release->setDescription($this->toParagraph($this->extract($chunk, '### ')));

        while ($section = $this->parseSection($chunk)) {
            $release->addSection($section);
        }

        return $release;
    }

    protected function parseSection(array &$buffer): ?Section
    {
        if (! $chunk = $this->extract($buffer, '### ')) {
            return null;
        }

        if (! Str::startsWith($chunk[0], '### ')) {
            throw new RuntimeException('Invalid changelog format. Expected a release category, but got: '.$chunk[0]);
        }

        $section = Section::make(Str::after(array_shift($chunk), '### '));
        $chunk = explode("\n", trim($this->toParagraph($chunk)));

        while ($entry = $this->parseEntry($chunk)) {
            $section->addEntry($entry);
        }

        return $section;
    }

    protected function parseEntry(array &$buffer): ?Entry
    {
        if (! $chunk = $this->extract($buffer, '- ')) {
            return null;
        }

        if (! Str::startsWith($chunk[0], '- ')) {
            throw new RuntimeException('Invalid changelog format. Expected an entry, but got: '.$chunk[0]);
        }

        $entry = $this->toParagraph($chunk);
        $anchor = null;

        preg_match('/\(\[(.*?)\]\((.*?)\)\)$/', $entry, $link);
        if ($link) {
            $entry = Str::replace($link[0], '', $entry);
            $anchor = Anchor::make($link[2], $link[1]);
        }

        return Entry::make(trim(Str::after($entry, '- ')), $anchor);
    }

    protected function toParagraph(array $chunk): string
    {
        return trim(implode("\n", $chunk));
    }
}

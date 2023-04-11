<?php

namespace ClaudioDekker\ChangelogUpdater\Components;

use Stringable;

class Section implements Stringable
{
    public string $title;

    protected array $entries = [];

    protected function __construct(string $title)
    {
        $this->title = trim($title);
    }

    public static function make(string $title): self
    {
        return new static($title);
    }

    public function addEntry(Entry|string $title, string|null $url = null): self
    {
        $this->entries[] = $title instanceof Entry
            ? $title
            : Entry::make($title, $url);

        return $this;
    }

    public function __toString()
    {
        if (empty($this->entries)) {
            return '';
        }

        $title = $this->title;
        $entries = implode("\n", $this->entries);

        return "### $title\n\n$entries\n";
    }
}

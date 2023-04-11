<?php

namespace ClaudioDekker\ChangelogUpdater\Components;

use Stringable;

class Entry implements Stringable
{
    protected Anchor $url;

    protected string $title;

    protected function __construct(
        string $title,
        Anchor|string|null $url = null,
    ) {
        $this->title($title)->url($url);
    }

    public static function make(string $title, Anchor|string|null $url = null): self
    {
        return new static($title, $url);
    }

    public function url(Anchor|string|null $url): self
    {
        $this->url = $url instanceof Anchor
            ? $url
            : Anchor::make($url);

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        if ($url = (string) $this->url) {
            $url = " ($url)";
        }

        $title = trim($this->title);

        return "- $title$url";
    }
}

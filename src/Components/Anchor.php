<?php

namespace ClaudioDekker\ChangelogUpdater\Components;

use Illuminate\Support\Str;
use Stringable;

class Anchor implements Stringable
{
    protected string $label = '';

    protected function __construct(
        protected ?string $href,
        ?string $label = null,
    ) {
        if ($label) {
            $this->label = $label;
        } elseif (! is_null($href)) {
            $this->label = $this->parseLabel($href);
        }
    }

    public static function make(?string $url = null, ?string $label = null): self
    {
        return new self($url, $label);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    protected function parseLabel(string $href): string
    {
        if (Str::startsWith($href, 'https://')) {
            return $this->parseLabel(Str::after($href, 'https://'));
        }

        if (Str::startsWith($href, 'http://')) {
            return $this->parseLabel(Str::after($href, 'http://'));
        }

        if (Str::startsWith($href, 'www.')) {
            return $this->parseLabel(Str::after($href, 'www.'));
        }

        if (Str::startsWith($href, 'github.com') && Str::contains($href, '/pull/')) {
            return '#'.Str::afterLast($href, '/pull/');
        }

        if (Str::startsWith($href, 'github.com') && Str::contains($href, '/issues/')) {
            return '#'.Str::afterLast($href, '/issues/');
        }

        return $href;
    }

    public function __toString(): string
    {
        if (! $this->href) {
            return '';
        }

        return "[$this->label]($this->href)";
    }
}

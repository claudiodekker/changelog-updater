<?php

namespace ClaudioDekker\ChangelogUpdater\Components;

use ClaudioDekker\ChangelogUpdater\ChangelogSectionComparator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Stringable;

class Release implements Stringable
{
    public string $title;

    protected string $description = '';

    protected Collection $sections;

    protected function __construct(string $title)
    {
        $this->sections = new Collection();
        $this->title = $title;
    }

    public static function make(string $title): static
    {
        return new static($title);
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function addSection(Section $section): self
    {
        $this->sections->put($section->title, $section);

        return $this;
    }

    public function section(string $search): Section
    {
        $normalized = Str::title($search);

        if (! $this->sections->has($normalized)) {
            $this->addSection(Section::make($normalized));
        }

        return $this->sections->get($normalized);
    }

    public function __toString()
    {
        $built = '## '.$this->title."\n";

        $sections = $this->sections->sortKeysUsing([ChangelogSectionComparator::class, 'compare']);
        if ($this->description || $sections->isNotEmpty()) {
            $built .= "\n";
        }

        if ($this->description) {
            $built .= $this->description."\n\n";
        }

        if ($sections->isNotEmpty()) {
            $built .= $sections
                ->map(fn (Section $section) => (string) $section)
                ->implode("\n")."\n";
        }

        return $built;
    }
}

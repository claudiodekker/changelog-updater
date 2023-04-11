<?php

namespace ClaudioDekker\ChangelogUpdater\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function fixture(string $filename): string
    {
        return __DIR__.'/_fixtures/'.$filename;
    }

    protected function loadFixture(string $filename): string
    {
        return file_get_contents($this->fixture($filename));
    }
}

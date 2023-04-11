<?php

namespace ClaudioDekker\ChangelogUpdater\Tests;

use ClaudioDekker\ChangelogUpdater\ChangelogSectionComparator;

class ChangelogSectionComparatorTest extends TestCase
{
    /** @test */
    public function it_compares_the_added_section(): void
    {
        $this->assertEquals(0, ChangelogSectionComparator::compare('Added', 'Added'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Added', 'Optimized'));
    }

    /** @test */
    public function it_compares_the_optimized_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Optimized', 'Added'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Optimized', 'Optimized'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Optimized', 'Reverted'));
    }

    /** @test */
    public function it_compares_the_reverted_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Reverted', 'Optimized'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Reverted', 'Reverted'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Reverted', 'Fixed'));
    }

    /** @test */
    public function it_compares_the_fixed_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Fixed', 'Optimized'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Fixed', 'Fixed'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Fixed', 'Changed'));
    }

    /** @test */
    public function it_compares_the_changed_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Changed', 'Optimized'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Changed', 'Changed'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Changed', 'Deprecated'));
    }

    /** @test */
    public function it_compares_the_deprecated_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Deprecated', 'Optimized'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Deprecated', 'Deprecated'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Deprecated', 'Removed'));
    }

    /** @test */
    public function it_compares_the_removed_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Removed', 'Optimized'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Removed', 'Removed'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Removed', 'Security'));
    }

    /** @test */
    public function it_compares_the_security_section(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Security', 'Optimized'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('Security', 'Security'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Security', 'Custom'));
    }

    /** @test */
    public function it_compares_the_custom_sections_to_standard_sections(): void
    {
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Added'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Optimized'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Reverted'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Fixed'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Changed'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Deprecated'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Removed'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('Custom', 'Security'));

        $this->assertEquals(-1, ChangelogSectionComparator::compare('Added', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Optimized', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Reverted', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Fixed', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Changed', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Deprecated', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Removed', 'Custom'));
        $this->assertEquals(-1, ChangelogSectionComparator::compare('Security', 'Custom'));
    }

    /** @test */
    public function it_compares_custom_sections_into_alphabetical_order(): void
    {
        $this->assertEquals(-1, ChangelogSectionComparator::compare('CustomA', 'CustomB'));
        $this->assertEquals(1, ChangelogSectionComparator::compare('CustomB', 'CustomA'));
        $this->assertEquals(0, ChangelogSectionComparator::compare('CustomA', 'CustomA'));
    }
}

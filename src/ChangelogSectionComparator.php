<?php

namespace ClaudioDekker\ChangelogUpdater;

class ChangelogSectionComparator
{
    private const ORDER = [
        'Added' => 0,
        'Optimized' => 1,
        'Reverted' => 2,
        'Fixed' => 3,
        'Changed' => 4,
        'Deprecated' => 5,
        'Removed' => 6,
        'Security' => 7,
    ];

    public static function compare(string $a, string $b): int
    {
        $aIndex = self::ORDER[$a] ?? false;
        $bIndex = self::ORDER[$b] ?? false;

        if ($aIndex === false && $bIndex === false) {
            return $a <=> $b;
        }

        if ($aIndex === false) {
            return 1;
        }

        if ($bIndex === false) {
            return -1;
        }

        return $aIndex <=> $bIndex;
    }
}

<?php

require '/app/vendor/autoload.php';

if (count($argv) < 5) {
    echo 'Usage: php action.php <changelog-contents> <section> <entry-title> [entry-link]';
    exit(1);
}

$file = $argv[1];
$section = $argv[2];
$title = $argv[3];
$link = ! empty($argv[4]) ? $argv[4] : null;

if (! file_exists($file)) {
    echo 'Provided changelog is empty';
    exit(1);
}

$changelog = \ClaudioDekker\ChangelogUpdater\ChangelogParser::parse(file_get_contents($file));
$changelog->unreleased()->section($section)->addEntry($title, $link);

file_put_contents($file, (string) $changelog);
exit(0);

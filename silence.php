<?php
/**
 * Created by PhpStorm.
 * User: Wojtek
 * Date: 2015-03-15
 * Time: 23:45
 */

srand(microtime(true));

$candidates = intval($argv[1]);
$voters = intval($argv[2]);

$names = array_slice(range('A', 'Z'), 0, $candidates);

file_put_contents('data.txt', '');

$silent = false;

$silentProb = 0.05;
$silentBoost = 0.5;

for ($i = 0; $i < $voters; $i++) {
    shuffle($names);
    if ($silent) {
        $boost = rand(0, 1000) / 1000 < $silentBoost;
        if ($boost) {
            $index = array_search($silent, $names);
            unset($names[$index]);
            array_unshift($names, $silent);
        }
    }
    file_put_contents('data.txt', implode(', ', $names) . PHP_EOL, FILE_APPEND);
    if (rand(0, 1000) / 1000 < $silentProb) {
        $silent = $names[rand(0, count($names) - 1)];
    }
}


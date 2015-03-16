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

for ($i = 0; $i < $voters; $i++) {
    shuffle($names);
    file_put_contents('data.txt', implode(', ', $names) . PHP_EOL, FILE_APPEND);
}

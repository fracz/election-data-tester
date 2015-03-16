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

$probs = [];
foreach ($names as $name) {
    $probs[$name] = 1;
}

for ($i = 0; $i < $voters; $i++) {
    $vote = [];
    shuffle($names);
    foreach ($names as $name) {
        $before = rand(0, 1000) / 1000 < $probs[$name];
        $probs[$name] *= ($before ? 1.1 : 0.9);
        if ($probs[$name] > 1) $probs[$name] = 1;
        if ($probs[$name] < 0.2) $probs[$name] = .2;
        $before ? array_unshift($vote, $name) : $vote[] = $name;
    }
    file_put_contents('data.txt', implode(', ', $vote) . PHP_EOL, FILE_APPEND);
}

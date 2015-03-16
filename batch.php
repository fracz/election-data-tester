<?php

$script = $argv[1];
$candidates = intval($argv[2]);
$voters = intval($argv[3]);
$repeats = intval($argv[4]);

$results = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
];

$avgTime = 0;

for ($i = 0; $i < $repeats; $i++) {
    echo "\r$i / $repeats";
    $result =  [];
    $start = microtime(true);
    exec("php $script.php $candidates $voters");
    $time = microtime(true) - $start;
    $avgTime = ($avgTime * ($i + 1) + $time) / ($i + 1);
    exec("php tester.php quiet", $result);
    $results[intval($result[0])]++;
}

echo "\r";

foreach ($results as $number => $qty) {
    echo $number . "\t" . $qty . PHP_EOL;
}

echo $avgTime;



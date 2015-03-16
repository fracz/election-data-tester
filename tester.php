<?php

namespace Election;

use DrooPHP\Count;

require 'vendor/autoload.php';

$contents = explode("\n", file_get_contents('data.txt'));

$first = getRow($contents[0]);

$results = [];

$condorcet = new \Condorcet\Condorcet();

foreach ($first as $name) {
    $results['borda'][$name] = 0;
    $results['plurality'][$name] = 0;
    $results['veto'][$name] = 0;
    $results['condorcet'][$name] = 0;
    $results['STV'][$name] = 0;
    $condorcet->addCandidate($name);
}

$candidates = count($first);
$stvContents = $candidates . ' 1' . PHP_EOL;

$stvlines = [];

foreach ($contents as $line) {
    $condorcet->parseVotes(str_replace(', ', ' > ', trim($line)));
    $line = getRow($line);
    if (count($line) == $candidates) {
        $nums = array_map(function ($e) use ($first) {
            return array_search($e, $first) + 1;
        }, $line);
        $stvLine = implode(' ', $nums);
        if (!isset($stvlines[$stvLine])) $stvlines[$stvLine] = 0;
        $stvlines[$stvLine]++;
        for ($i = 0; $i < $candidates; $i++) {
            $name = $line[$i];
            $results['borda'][$name] += $candidates - $i - 1;
            $results['plurality'][$name] += $i == 0 ? 1 : 0;
            $results['veto'][$name] += $i == $candidates - 1 ? 0 : 1;
        }
    }
}

$condorcet = \Condorcet\Condorcet::format($condorcet->getResult(), false);
for ($i = 1; $i <= count($condorcet); $i++) {
    $name = $condorcet[$i];
    if (!is_array($name)) {
        $name = [$name];
    }
    foreach ($name as $n) {
        $results['condorcet'][$n] += $candidates - $i - 1 + count($name);
    }
}

//$dictator = rand(0, count($contents) - 2);
//$results['dictator'][getRow($contents[$dictator])[0]] = 1000000;

foreach ($stvlines as $line => $qty) {
    $stvContents .= $qty . ' ' . $line . ' 0' . PHP_EOL;
}

$stvContents .= '0' . PHP_EOL;
foreach ($first as $name) {
    $stvContents .= $name . PHP_EOL;
}
$stvContents .= '"Kto wymyslil ten format"' . PHP_EOL;
file_put_contents('stv.txt', $stvContents);

$stv = new Count();
$stv->getSource()->setOptions(['filename' => 'stv.txt']);
$stvElected = current($stv->getResult()->getElected())->name;
$results['STV'][$stvElected] = 1;


$winners = [];

$echo = !isset($argv[1]);

foreach ($results as $method => $res) {
    asort($res);
    $res = array_reverse($res);
    $winner = key($res);
    $winners[] = $winner;
    if ($echo) {
        echo $winner . ' wins in ' . $method . '; ';
        foreach ($res as $name => $points) {
            echo $name . ': ' . $points . '; ';
        }
        echo PHP_EOL;
    }
}

$winners = count(array_unique($winners));

if ($echo) {
    echo 'Dataset is ';

    switch ($winners) {
        case 3:
            echo 'GOOD';
            break;
        case 4:
            echo 'VERY GOOD';
            break;
        case 5:
            echo 'EXCELLENT!';
            break;
        default:
            echo 'BAD';
    }
} else {
    echo $winners;
}
function getRow($row)
{
    return array_filter(array_map(function ($e) {
        return trim($e);
    }, explode(', ', trim($row))));
}

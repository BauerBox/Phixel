<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\SPI\LPD6803\LPD6803Driver as Driver1;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver as Driver2;
use BauerBox\Phixel\Driver\LPD6803Driver as Driver3;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Font8x5;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();

$drivers = array(
    'Legacy SPI'    =>  new Driver1(0, 16000000, 192),
    'Legacy Raw'    =>  new Driver2('/dev/spidev0.0', 192),
    'New (SPI Mode)'    =>  new Driver3(192, Driver3::MODE_SPI, 0),
    'New (RAW Mode)'    =>  new Driver3(192, Driver3::MODE_RAW, 0)
);

$stats = array();

foreach ($drivers as $description => $driver) {
    echo "Starting Driver Test: {$description}" . PHP_EOL;

    $phixel = new Phixel($driver);
    $phixel->allOff();

    $font = new Font8x5(24, false, array('BANG!'), 0xffffff);

    $frame = new FrameBuffer($phixel);
    $frame->attachObject($font);

    echo " > Starting Stopwatch" . PHP_EOL;
    $stats[$description] = array(
        'start' => microtime(true),
        'stop'  =>  null
    );
    $frame->startLoop();
    $stats[$description]['stop'] = microtime(true);
    echo " > Stopwatch Stopped" . PHP_EOL;

    unset($frame);
    unset($font);
    unset($phixel);
    unset($driver);
}

echo "And the results are in: " . PHP_EOL;
foreach ($stats as $description => $times) {
    echo PHP_EOL . "Driver: {$description}" . PHP_EOL;
    echo ' TIME: ' . sprintf('%06.4f', (float) ($times['stop'] - $times['start'])) . PHP_EOL;
}

$drivers = array_reverse($drivers);

$stats = array();

foreach ($drivers as $description => $driver) {
    echo "Starting Driver Test: {$description}" . PHP_EOL;

    $phixel = new Phixel($driver);
    $phixel->allOff();

    $font = new Font8x5(24, false, array('BANG!'), 0xffffff);

    $frame = new FrameBuffer($phixel);
    $frame->attachObject($font);

    echo " > Starting Stopwatch" . PHP_EOL;
    $stats[$description] = array(
        'start' => microtime(true),
        'stop'  =>  null
    );
    $frame->startLoop();
    $stats[$description]['stop'] = microtime(true);
    echo " > Stopwatch Stopped" . PHP_EOL;

    unset($frame);
    unset($font);
    unset($phixel);
    unset($driver);
}

echo "And the results are in: " . PHP_EOL;
foreach ($stats as $description => $times) {
    echo PHP_EOL . "Driver: {$description}" . PHP_EOL;
    echo ' TIME: ' . sprintf('%06.4f', (float) ($times['stop'] - $times['start'])) . PHP_EOL;
}

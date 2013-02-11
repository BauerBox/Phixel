<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\WS2801Driver as Driver;
use BauerBox\Phixel\Color\Wheel;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();

$phixel = new Phixel(new Driver(25, Driver::MODE_SPI, 0));
$phixel->allOff();

$wheel = new Wheel;

while (true) {
    $time = microtime(true);

    for ($i = 0; $i < 256; ++$i) {
        $phixel->fill($wheel($i));
    }

    $time = microtime(true) - $time;
    echo "FPS: " . ($time / 256) . PHP_EOL;
}

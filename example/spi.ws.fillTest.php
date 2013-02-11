<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\WS2801Driver as Driver;
use BauerBox\Phixel\Color\Wheel;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::enableDebugOutput();

$phixel = new Phixel(new Driver(25, Driver::MODE_SPI, 0));
$phixel->allOff();

$wheel = new Wheel;

for ($i = 0; $i < 256; ++$i) {
    $phixel->fill($wheel($i));
}

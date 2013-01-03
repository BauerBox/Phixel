<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Color\Wheel96;

include_once __DIR__ . '/include/bootstrap.php';

Phixel::enableDebugOutput();

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));
$wheel = new Wheel96;


for ($i = 0; $i < 128; ++$i) {
    Debug::log('Filling with wheel position: ' . $i);
    Debug::logBinary($wheel($i), 24);
    $phixel->fill($wheel($i));

}

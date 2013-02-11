<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\WS2801Driver as Driver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Bar;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::enableDebugOutput();

$phixel = new Phixel(new Driver(25, Driver::MODE_SPI, 0));
$phixel->allOff();

for ($i = 0x000000; $i < 0xffffff; $i += 0x0000f) {
    $phixel->fill($i);
}

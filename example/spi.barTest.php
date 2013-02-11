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

$frame = new FrameBuffer($phixel);
$frame->attachObject(new Bar(0xff0000, 5, 0, 1));

$frame->startLoop();

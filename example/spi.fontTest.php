<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803Driver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Font8x5;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::enableDebugOutput();

$font = new Font8x5(24, true, array('Rev!Co Rocks!'));

$phixel = new Phixel(new LPD6803Driver(192, LPD6803Driver::MODE_SPI, 0));
$phixel->allOff();

$frame = new FrameBuffer($phixel);

$frame->attachObject($font);

$frame->startLoop();

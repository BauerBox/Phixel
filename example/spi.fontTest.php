<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803Driver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Font8x5;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::enableDebugOutput();

$font = new Font8x5(24, true, array('Rev!Co Rocks!'));

$driver = new LPD6803Driver();
$driver->setDevice(0)->setPixelCount(192)->setMode(LPD6803Driver::MODE_SPI);

$phixel = new Phixel($driver);
$phixel->allOff();

exit(0);

$frame = new FrameBuffer($phixel);

$frame->attachObject($font);

$frame->startLoop();

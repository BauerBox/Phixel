<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Bar;
use BauerBox\Phixel\Object\Pentagon;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::setMaxBrightness(0.5);

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 192));
$phixel->allOff();

$frame = new FrameBuffer($phixel);
$frame->attachObject(new Bar(0xff0000, 5, 0, 1));
$frame->attachObject(new Bar(0x00ff00, 5, 38, 1));
$frame->attachObject(new Bar(0x0000ff, 5, 76, 1));
$frame->attachObject(new Bar(0xffffff, 10, 95, -1));

$frame->startLoop();

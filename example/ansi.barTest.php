<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\Debug\AnsiDriver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Bar;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::setMaxBrightness(1.0);
//Phixel::enableDebugOutput();

$driver = new AnsiDriver((int) (1000000 / 15));
$driver->setMatrixSize(24, 8);
$driver->setClearOnReset();

$phixel = new Phixel($driver);
$phixel->allOff();

$frame = new FrameBuffer($phixel);

$frame->attachObject(new Bar(0xff0000, 5, 0, 1));
$frame->attachObject(new Bar(0x00ff00, 5, 60, 1));
$frame->attachObject(new Bar(0x0000ff, 5, 120, 1));
$frame->attachObject(new Bar(0xffffff, 5, (192 / 2), -1));

$frame->startLoop();

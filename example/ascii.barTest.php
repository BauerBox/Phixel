<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\Debug\AsciiDriver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Bar;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::setMaxBrightness(0.5);

$driver = new AsciiDriver((int) (1000000 / 30));
$driver->setMatrixSize(24, 8);

$phixel = new Phixel($driver);
$phixel->allOff();

$frame = new FrameBuffer($phixel);

$frame->attachObject(new Bar(0xffffff, 5, 0, 1));

$frame->startLoop();

<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\Debug\AsciiDriver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Font8x5;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::setMaxBrightness(0.5);
Phixel::enableDebugOutput();

$driver = new AsciiDriver((int) (1000000 / 30));
$driver->setMatrixSize(24, 8);

$phixel = new Phixel($driver);
$phixel->allOff();

$frame = new FrameBuffer($phixel);

$font = new Font8x5(24);
$font->addString('Hello World!');
$font->addString('Nice to Meet You!');
$frame->attachObject($font);

$frame->startLoop();

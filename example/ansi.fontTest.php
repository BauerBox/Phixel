<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\Debug\AnsiDriver;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\Font8xV;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();

$driver = new AnsiDriver((int) (1000000 / 15));
$driver->setMatrixSize(24, 8)->setClearOnReset();

$phixel = new Phixel($driver);
$phixel->allOff();

$frame = new FrameBuffer($phixel);

$font = new Font8xV(24);

$font->setColors(
    array(
        0xff0000,
        0x00ff00,
        0x0000ff,
        0xffffff
    )
);

$font->addString('abcdefghijklmnopqrstuvwxyz 0123456789 !@#$%^&*()_+-=[]\\{}|;\':",.<>/?`~ ABCDEFGHIJKLMNOPQRSTUVWXYZ');
$frame->attachObject($font);

$frame->startLoop();

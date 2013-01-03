<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Buffer\FrameBuffer;

include 'include/bootstrap.php';

Phixel::enableDebugOutput();

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));

$frameBuffer = new FrameBuffer($phixel);

$frameBuffer->attachObject(new BauerBox\Phixel\Object\Effect\WheelSnake(0xffffff, 25, 0, 1));
$frameBuffer->startLoop();

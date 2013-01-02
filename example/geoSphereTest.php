<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Object\GeodesicSphere;
use BauerBox\Phixel\Buffer\FrameBuffer;

include 'include/bootstrap.php';

Phixel::enableDebugOutput();

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));

$frameBuffer = new FrameBuffer($phixel);

$sphere = new GeodesicSphere('Resources:Config:GeodesicSphereZones', array(0xff0000, 0x00ff00, 0x0000ff));

$frameBuffer->attachObject($sphere);
$frameBuffer->startLoop();

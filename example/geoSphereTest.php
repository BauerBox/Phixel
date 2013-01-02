<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Object\GeodesicSphere;
use BauerBox\Phixel\Buffer\FrameBuffer;

include 'include/bootstrap.php';

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));

$frameBuffer = new FrameBuffer($phixel);

$sphere = new GeodesicSphere(Phixel::getFilePath('Resources:Config:GeodesicSphereZones', 'ini'));

$frameBuffer->attachObject($sphere);
$frameBuffer->startLoop();

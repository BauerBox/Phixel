<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Object\GeodesicSphere;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Pixel\Pixel;

include 'include/bootstrap.php';

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));

$frameBuffer = new FrameBuffer($phixel);

$pixel = new Pixel;
$colors = array();

foreach (array(0x0000ff, 0x000ff0, 0x00ff00, 0x0ff000, 0xff0000, 0xf0000f) as $color) {
    for ($i = 0; $i < 1.05; $i += 0.1) {
        $colors[] = $pixel->setColor($color)->setBrightness($i)->getCompiledColor();
    }
}


$sphere = new GeodesicSphere('Resources:Config:GeodesicSphereZones', $colors);

$frameBuffer->attachObject($sphere);
$frameBuffer->startLoop();

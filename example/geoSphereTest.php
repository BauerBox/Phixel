<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;
use BauerBox\Phixel\Object\GeodesicSphere;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Debug\Debug;

include 'include/bootstrap.php';

Phixel::enableDebugOutput();

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));

$frameBuffer = new FrameBuffer($phixel);

$pixel = new Pixel;
$colors = array();

foreach (array(0x0000ff, 0x000ff0, 0x00ff00, 0x0ff000, 0xff0000, 0xf0000f) as $color) {
    for ($i = 0.25; $i < 1.05; $i += 0.25) {
        $colors[] = $pixel->setColor($color)->setBrightness($i)->getCompiledColor();
    }

    for ($i = 1.0; $i > 0.20; $i -= 0.25) {
        $colors[] = $pixel->setColor($color)->setBrightness($i)->getCompiledColor();
    }
}

foreach ($colors as $color) {
    Debug::logBinary($color, 24);
}

$sphere = new GeodesicSphere('Resources:Config:GeodesicSphereZones', $colors);

$frameBuffer->attachObject($sphere);
$frameBuffer->startLoop();

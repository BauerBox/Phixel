<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::enableDebugOutput();
Phixel::setMaxBrightness(0.5);

$testFile = __DIR__ . '/out.check.raw';

if (true === file_exists($testFile)) {
    unlink($testFile);
}

touch($testFile);

$phixel = new Phixel(new LPD6803Driver($testFile, 156));
$pixel = $phixel->getNewPixel(0x123456);

$pixel->setColorRGB(255, 127, 64);

print_r($pixel->getChannelValues());

$phixel->allOn();
$phixel->fill(0x0000ff);

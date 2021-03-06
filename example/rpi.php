<?php

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Driver\LPD6803\LPD6803Driver;

include_once __DIR__ . '/../lib/Phixel.php';

Phixel::installAutloader();
Phixel::setMaxBrightness(0.5);

$phixel = new Phixel(new LPD6803Driver('/dev/spidev0.0', 156));

$phixel->allOff();

$pixel = $phixel->getNewPixel();

for ($red = 0x00; $red < 0xff; $red += 8) {
    for ($green = 0x00; $green < 0xff; $green += 8) {
        for ($blue = 0x00; $blue < 0xff; $blue += 8) {
            \BauerBox\Phixel\Debug\Debug::log("Filling with RGB: ({$red},{$green},{$blue})");
            $phixel->fill($pixel->setColorRGB($red, $green, $blue)->getColor());
            usleep(1000000 / 30);
        }
    }
}

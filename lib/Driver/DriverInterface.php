<?php

namespace BauerBox\Phixel\Driver;

use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;

interface DriverInterface
{
    public function openSocket();
    public function closeSocket();

    public function processPixel(Pixel $pixel);

    public function setDevice($device);

    public function getPixelCount();
    public function setPixelCount($pixelCount);

    public function writeData($data);
    public function writePixelStream(PixelStream $stream, $flush);
    public function writeReset();

    public function flush();
}

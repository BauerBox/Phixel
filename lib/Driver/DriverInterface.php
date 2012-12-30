<?php

namespace BauerBox\Phixel\Driver;

use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;

interface DriverInterface
{
    public function closeSocket();
    public function flush();
    public function getPixelCount();
    public function processPixel(Pixel $pixel);
    public function setDevice($device);
    public function setPixelCount($pixelCount);
    public function openSocket();
    public function writeData($data);
    public function writePixelStream(PixelStream $stream);
    public function writeReset();
}

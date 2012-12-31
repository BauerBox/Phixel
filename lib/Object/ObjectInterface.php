<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Buffer\FrameBuffer;

interface ObjectInterface
{
    public function setup($pixelCount);
    public function processFrame(FrameBuffer $buffer);
}

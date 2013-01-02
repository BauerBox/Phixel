<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\AbstractObject;
use BauerBox\Phixel\Buffer\FrameBuffer;

class Pentagon extends AbstractObject
{
    const ORIENTATION_POINT_NORTH = 1;
    const ORIENTATION_POINT_SOUTH = 2;

    protected $map;
    protected $orientation;

    public function __construct(array $map = null, $orientation = self::ORIENTATION_POINT_NORTH)
    {
        if (null === $map) {
            $this->map = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
        } else {
            $this->map = $map;
        }

        $this->orientation = $orientation;
    }

    public function processFrame(FrameBuffer $buffer)
    {
        foreach ($this->map as $index) {
            $buffer->getPixel($index)->setColor(0x666666)->setBrightness(0.5);
        }
    }
}

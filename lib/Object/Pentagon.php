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
    protected $pendingColors;
    protected $pendingBrightness;

    public function __construct(array $map = null, $orientation = self::ORIENTATION_POINT_NORTH)
    {
        if (null === $map) {
            $this->map = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
        } else {
            $this->map = $map;
        }

        $this->orientation = $orientation;
        $this->pendingColors = array();
        $this->pendingBrightness = array();
    }

    public function processFrame(FrameBuffer $buffer)
    {
        foreach ($this->map as $index) {
            if (true === array_key_exists($index, $this->pendingColors)) {
                $buffer->getPixel($index)->setColor($this->pendingColors[$index]);
                unset($this->pendingColors[$index]);
            }

            if (true === array_key_exists($index, $this->pendingBrightness)) {
                $buffer->getPixel($index)->setBrightness($this->pendingBrightness[$index]);
                unset($this->pendingBrightness[$index]);
            }
        }
    }

    public function fill($color, $brightness = null)
    {
        foreach ($this->map as $pixel => $object) {
            $this->pendingColors[$pixel] = $color;

            if (null !== $brightness) {
                $this->pendingBrightness[$pixel] = $brightness;
            }
        }
    }
}

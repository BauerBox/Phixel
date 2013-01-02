<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\AbstractObject;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Debug\Debug;

class Pentagon extends AbstractObject
{
    const ORIENTATION_POINT_NORTH = 1;
    const ORIENTATION_POINT_SOUTH = 2;

    protected $map;
    protected $orientation;
    protected $pendingColors;
    protected $pendingBrightness;
    protected $center;
    protected $innerRing;
    protected $outerRing;

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
        $this->outerRing = array();
        $this->innerRing = array();

        for ($i = 1; $i < 6; ++$i) {
            $this->innerRing[] = $this->map[$i];
        }

        for ($i = 6; $i < 16; ++$i) {
            $this->outerRing[] = $this->map[$i];
        }

        $this->center = $this->map[0];
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

    public function fill($color, $brightness = 1.0)
    {
        Debug::log('Filling pentagon with color: ' . $color);

        foreach ($this->map as $pixel) {
            $this->pendingColors[$pixel] = $color;

            if (null !== $brightness) {
                $this->pendingBrightness[$pixel] = $brightness;
            }
        }
    }

    public function drawOuterRing($color, $brightness = 1.0)
    {
        Debug::log('Drawing outer ring with color: ' . $color);

        foreach ($this->outerRing as $pixelIndex) {
            $this->pendingColors[$pixel] = $color;

            if (null !== $brightness) {
                $this->pendingBrightness = $brightness;
            }
        }
    }

    public function drawInnerRing($color, $brightness = 1.0)
    {
        Debug::log('Drawing inner ring with color: ' . $color);

        foreach ($this->innerRing as $pixelIndex) {
            $this->pendingColors[$pixel] = $color;

            if (null !== $brightness) {
                $this->pendingBrightness = $brightness;
            }
        }
    }

    public function drawCenter($color, $brightness = 1.0)
    {
        $this->pendingColors[$this->center] = $color;

        if (null !== $brightness) {
            $this->pendingBrightness[$this->center] = $brightness;
        }
    }
}

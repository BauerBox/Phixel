<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\ObjectInterface;

abstract class AbstractObject implements ObjectInterface
{
    protected $pixelCount;

    public function setup($pixelCount)
    {
        $this->pixelCount = $pixelCount;
    }

    protected function nextPixel($pixel)
    {
        if ($pixel == ($this->pixelCount - 1)) {
            return 0;
        }

        return ++$pixel;
    }

    protected function previousPixel($pixel)
    {
        if ($pixel == 0) {
            return ($this->pixelCount - 1);
        }

        return --$pixel;
    }
}

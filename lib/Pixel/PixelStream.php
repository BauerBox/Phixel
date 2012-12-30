<?php

namespace BauerBox\Phixel\Pixel;

use BauerBox\Phixel\Pixel\Pixel;

class PixelStream
{
    protected $compiled;
    protected $pixels;
    protected $pixelCount;

    public function __construct($pixelCount, $color = 0xffffff, $brightness = 1.0)
    {
        if (false === is_int($pixelCount) || $pixelCount < 1) {
            throw new \Exception('Invalid PixelStream size: ' . $pixelCount);
        }

        $this->pixels = array();

        for ($i = 0; $i < $pixelCount; ++$i) {
            $this->pixels[$i] = new Pixel($color, $brightness);
        }
    }

    public function &getPixel($index)
    {
        if (false === array_key_exists($index, $this->pixels)) {
            throw new \Exception('Pixel index out of range: ' . $index);
        }

        return $this->pixels[$index];
    }

    public function &getPixelArray()
    {
        return $this->pixels;
    }

    public function compile()
    {
        $this->compiled = array();

        foreach ($this->pixels as $index => &$pixel) {
            $this->compiled[$index] = $pixel->getCompiledColor();
        }

        return $this->compiled;
    }
}

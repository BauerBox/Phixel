<?php

namespace BauerBox\Phixel\Color;

use BauerBox\Phixel\Pixel\Pixel;

class Wheel
{
    protected $pixel;

    public function __construct()
    {
        $this->pixel = new Pixel;
    }

    public function __invoke($position)
    {
        if ($position < 85) {
            return $this->pixel->setColorRGB($position * 3, 255 - $position * 3, 0)->getColor();
		} elseif ($position < 170) {
			$position -= 85;
            return $this->pixel->setColorRGB(255 - $position * 3, 0, $position * 3)->getColor();
		} else {
			$position -= 170;
            return $this->pixel->setColorRGB(0, $position * 3, 255 - $position * 3)->getColor();
		}
    }

    public function next($position)
    {
        if ($position == 255) {
            return 0;
        }

        return $position + 1;
    }
}

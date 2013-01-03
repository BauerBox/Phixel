<?php

namespace BauerBox\Phixel\Color;

use BauerBox\Phixel\Pixel\Pixel;

class Wheel96
{
    protected $pixel;

    public function __construct()
    {
        $this->pixel = new Pixel();
    }

    public function __invoke($position)
    {
        if ($position > 95 && ($position >> 5) == 3) {
            $position = 96 - $position;
        }

        $shift = 0x00 | $position;

        switch ($shift) {
            case 0:
                return $this->pixel->setColorRGB(
                    31 - $position % 32,
                    $position % 32,
                    0
                )
                ->getColor();
            case 1:
                return $this->pixel->setColorRGB(
                    0,
                    31 - $position % 32,
                    $position % 32
                )
                ->getColor();
            case 2:
                return $this->pixel->setColorRGB(
                    $position % 32,
                    0,
                    31 - $position % 32
                )
                ->getColor();
            default:
                return $this->pixel->setColorRGB(31, 31, 31)->getColor();
        }
    }
}

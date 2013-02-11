<?php

namespace BauerBox\Phixel\Pixel;

use BauerBox\Phixel\Phixel;

class Pixel
{
    protected $color;
    protected $compiled;
    protected $compiledChannels;
    protected $brightness;

    public function __construct($color = 0xffffff, $brightness = 1.0)
    {
        return $this->setColor($color)->setBrightness($brightness);
    }

    public function getCompiledColor($channels = false)
    {
        if (null === $this->compiled) {
            $color = $this->color;

            $this->setColorRGB(
                (int) ($this->getRedChannel() * $this->getBrightness()),
                (int) ($this->getGreenChannel() * $this->getBrightness()),
                (int) ($this->getBlueChannel() * $this->getBrightness())
            );

            $this->compiled = $this->getColor();
            $this->compiledChannels = $this->getChannelValues();

            $this->color = $color;
        }

        return (true === $channels) ? $this->compiledChannels : $this->compiled;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getRedChannel()
    {
        return (0xff0000 & $this->color) >> 16;
    }

    public function getGreenChannel()
    {
        return (0x00ff00 & $this->color) >> 8;
    }

    public function getBlueChannel()
    {
        return (0x0000ff & $this->color);
    }

    public function getChannelValues()
    {
        return array(
            'r' => $this->getRedChannel(),
            'g' => $this->getGreenChannel(),
            'b' => $this->getBlueChannel(),
            'full' => $this->getColor()
        );
    }

    public function setColor($color)
    {
        // Reset Compiled
        $this->compiled = null;

        if ($color > 0xffffff || $color < 0x000000) {
            throw new \Exception('Color is out of range: ' . $color);
        }

        $this->color = $color;

        return $this;
    }

    public function setColorRGB($red, $green, $blue)
    {
        $this->compiled = null;

        $red = (0xff0000 & ($red << 16));
        $green = (0x00ff00 & ($green << 8));
        $blue = (0x0000ff & $blue);

        $this->color = (0x000000 | $red | $green | $blue);

        return $this;
    }

    public function setBrightness($brightness)
    {
        $this->compiled = null;

        if ($brightness > 1.0 || $brightness < 0.0) {
            throw new \Exception('Brightness is out of range: ' . $brightness);
        }

        $this->brightness = $brightness;

        return $this;
    }

    public function getBrightness()
    {
        return $this->brightness * Phixel::getMaxBrightness();
    }
}

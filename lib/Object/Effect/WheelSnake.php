<?php

namespace BauerBox\Phixel\Object\Effect;

use BauerBox\Phixel\Object\Bar;

class WheelSnake extends Bar
{
    protected $wheelPosition = 0;
    protected $wheel = null;

    public function processFrame(\BauerBox\Phixel\Buffer\FrameBuffer $buffer)
    {
        if (null === $this->wheel) {
            $this->wheel = new \BauerBox\Phixel\Color\Wheel96;
        }
        $wheel = $this->wheel;

        // Get the current position
        $buffer->getPixel($this->position)->setColor($wheel($this->getNextWheelPosition()))->setBrightness(1.0);

        // Process The Tail
        $tailPosition = 1;
        foreach ($this->tail as $index) {
            if ($tailPosition == $this->length) {
                $buffer->removePixel($index);
                ++$tailPosition;
            } else {
                $buffer->getPixel($index)->setBrightness(1 / $tailPosition++);
            }
        }

        array_unshift($this->tail, $this->position);

        if ($this->direction > 0) {
            $this->position = $this->nextPixel($this->position);
        } else {
            $this->position = $this->previousPixel($this->position);
        }

        if (count($this->tail) > $this->length) {
            array_pop($this->tail);
        }
    }

    protected function getNextWheelPosition()
    {
        if ($this->wheelPosition == 95) {
            $this->wheelPosition = 0;
        } else {
            ++$this->wheelPosition;
        }

        return $this->wheelPosition;
    }
}

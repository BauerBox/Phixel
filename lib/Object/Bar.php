<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Object\AbstractObject;

class Bar extends AbstractObject
{
    protected $color;
    protected $direction;
    protected $length;
    protected $position;
    protected $tail;

    public function __construct($color = 0xffffff, $length = 5, $position = 0, $direction = 1)
    {
        $this->color = $color;
        $this->length = $length;
        $this->direction = $direction;
        $this->tail = array();
        $this->position = $position;
    }

    public function setup($pixelCount)
    {
        parent::setup($pixelCount);
    }

    public function processFrame(FrameBuffer $buffer)
    {
        // Get the current position
        $buffer->getPixel($this->position)->setColor($this->color)->setBrightness(1.0);

        // Process The Tail
        $tailPosition = 1;
        foreach ($this->tail as $index) {
            if ($tailPosition == $this->length) {
                $buffer->getPixel($index)->setBrightness(0);
                ++$tailPosition;
            } else {
                $buffer->getPixel($index)->setBrightness($tailPosition++);
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
}

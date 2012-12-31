<?php

namespace BauerBox\Phixel\Buffer;

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Pixel\PixelStream;
use BauerBox\Phixel\Object\ObjectInterface;

class FrameBuffer
{
    protected $buffer;
    protected $continue;
    protected $currentObject;

    /**
     * @var \BauerBox\Phixel\Pixel\PixelStream
     */
    protected $frame;
    protected $phixel;
    protected $pixelCount;
    protected $objects;

    public function __construct(Phixel &$phixel)
    {
        $this->pixelCount = $phixel->getPixelCount();
        $this->phixel =& $phixel;

        $this->initializeBuffer();
    }

    public function attachObject(ObjectInterface $object)
    {
        $this->objects[] = $object;

        $object->setup($this->pixelCount);

        return $this;
    }

    public function getPixel($pixel)
    {
        if (null === $this->currentObject) {
            throw new \Exception('Can not get pixel while not in loop');
        }

        if (false === array_key_exists($this->currentObject, $this->buffer[$pixel]['pixels'])) {
            $this->buffer[$pixel]['pixels'][$this->currentObject] = $this->phixel->getNewPixel(0x000000);
        }

        return $this->buffer[$pixel]['pixels'][$this->currentObject];
    }

    public function startLoop()
    {
        if (count($this->objects) < 1) {
            throw new \Exception('Can not start loop without any objects!');
        }

        $this->continue = true;

        while (true === $this->continue) {
            foreach ($this->objects as $index => $object) {
                $this->currentObject = $index;
                $object->processFrame($this);
            }

            $this->compileFrame();

            $this->flushFrame();
        }
    }

    protected function compileFrame()
    {
        foreach ($this->buffer as $index => $data) {
            // Check if this pixel needs compiling
            if (true === $data['compiled']) {
                continue;
            }

            $compiled = array(
                'r' => 0,
                'g' => 0,
                'b' => 0,
                'a' => 0
            );

            foreach ($data['pixel'] as $pixel) {
                $compiled['r'] += $pixel->getRedChannel();
                $compiled['g'] += $pixel->getGreenChannel();
                $compiled['b'] += $pixel->getBlueChannel();
                $compiled['a'] += $pixel->getBrightness();
            }

            $this
                ->frame
                ->getPixel($index)
                ->setColorRGB(
                    ((int) $compiled['r'] / count($data['pixels'])),
                    ((int) $compiled['g'] / count($data['pixels'])),
                    ((int) $compiled['b'] / count($data['pixels']))
                )
                ->setBrightness(
                    $compiled['a'] / count($data['pixels'])
                );
        }
    }

    protected function flushFrame()
    {
        $this->phixel->getDriver()->writePixelStream($this->frame)->flush();
    }

    protected function initializeBuffer()
    {
        $this->buffer = array();

        for ($i = 0; $i < $this->pixelCount; ++$i) {
            $this->buffer[$i] = array(
                'compiled' => true,
                'pixels' => array()
            );
        }

        $this->frame = new PixelStream($this->pixelCount);
        $this->objects = array();
    }
}

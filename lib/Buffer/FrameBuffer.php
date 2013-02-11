<?php

namespace BauerBox\Phixel\Buffer;

use BauerBox\Phixel\Phixel;
use BauerBox\Phixel\Pixel\PixelStream;
use BauerBox\Phixel\Object\ObjectInterface;
use BauerBox\Phixel\Debug\Debug;

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
    protected $loopStart;
    protected $loopIterations;

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

    /**
     *
     * @param type $pixel
     * @return Pixel
     * @throws \Exception
     */
    public function getPixel($pixel)
    {
        if (null === $this->currentObject) {
            throw new \Exception('Can not get pixel while not in loop');
        }

        $pixel = (int) $pixel;

        if (false === array_key_exists($this->currentObject, $this->buffer[$pixel]['pixels'])) {
            $this->buffer[$pixel]['pixels'][$this->currentObject] = $this->phixel->getNewPixel(0x000000);
        }

        $this->buffer[$pixel]['compiled'] = false;
        return $this->buffer[$pixel]['pixels'][$this->currentObject];
    }

    public function removePixel($pixel)
    {
        if (null === $this->currentObject) {
            throw new \Exception('Can not get pixel while not in loop');
        }

        $pixel = (int) $pixel;

        if (false === array_key_exists($this->currentObject, $this->buffer[$pixel]['pixels'])) {
            throw new \Exception('Invalid pixel index for current object');
        }

        $this->buffer[$pixel]['compiled'] = false;
        unset($this->buffer[$pixel]['pixels'][$this->currentObject]);
    }

    public function startLoop()
    {
        if (count($this->objects) < 1) {
            throw new \Exception('Can not start loop without any objects!');
        }

        $this->continue = true;

        $this->loopStart = microtime(true);
        $this->loopIterations = 0;

        while (true === $this->continue) {
            foreach ($this->objects as $index => $object) {
                $this->currentObject = $index;
                $object->processFrame($this);
            }

            $this->compileFrame();
            $this->flushFrame();
        }
    }

    public function stopLoop()
    {
        $this->continue = false;
    }

    protected function compileFrame()
    {
        foreach ($this->buffer as $index => &$data) {
            // Check if this pixel needs compiling
            if (true === $data['compiled']) {
                continue;
            }

            // Check if there are pixels
            if (count($data['pixels']) < 1) {
                $this->frame->getPixel($index)->setColor(0x000000)->setBrightness(1.0);
                $data['compiled'] = true;
                continue;
            }

            $compiled = array(
                'r' => 0,
                'g' => 0,
                'b' => 0,
                'a' => 0
            );

            foreach ($data['pixels'] as $pixel) {
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

            $data['compiled'] = true;
        }
    }

    protected function flushFrame()
    {
        $this->phixel->getDriver()->writePixelStream($this->frame)->flush();

        // Calculate FPS
        if (Debug::isEnabled()) {
            $time = (microtime(true) - $this->loopStart);
            $fps = ++$this->loopIterations / $time;
            Debug::log("Estimated FPS: " . sprintf('%04.2f', $fps));
        }
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

        $this->frame = new PixelStream($this->pixelCount, 0x000000);
        $this->objects = array();
    }
}

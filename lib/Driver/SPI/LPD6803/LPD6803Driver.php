<?php

namespace BauerBox\Phixel\Driver\SPI\LPD6803;

use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Driver\DriverInterface;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;

class LPD6803Driver implements DriverInterface
{
    protected $buffer;
    protected $channel;
    protected $pixelCount;
    protected $pixelMaskRed = 0b1111110000000000;
    protected $pixelMaskGreen = 0b1000001111100000;
    protected $pixelMaskBlue = 0b1000000000011111;
    protected $pixelMaskBase = 0b1000000000000000;
    protected $socket;
    protected $speed;
    protected $reset;

    public function __construct($channel = 0, $speed = 8000000, $pixelCount = 25)
    {
        $this->channel = $channel;
        $this->speed = $speed;
        $this->pixelCount = $pixelCount;
        $this->setup();
    }

    public function closeSocket()
    {
        return $this;
    }

    public function flush()
    {
        Debug::log('Flushing buffer to device');

        if ($this->buffer == '') {
            throw new \Exception('There is no data in the buffer to flush');
        }

        $bufferCount = strlen($this->buffer);
        wiringPiSPIDataRW($this->channel, $this->buffer, $bufferCount);

        $this->buffer = '';
        $this->writeReset();

        return $this;
    }

    public function getPixelCount()
    {
        return $this->pixelCount;
    }

    public function openSocket()
    {
        if (wiringPiSPISetup(0, 8000000) < 0) {
            throw new \Exception('There was an error running setup for SPI');
        }

        wiringPiSetupSys();

        return $this;
    }

    public function processPixel(Pixel $pixel)
    {
        // Convert compiled color to 15-bit used by LPD6803
        $channels = $pixel->getCompiledColor(true);

        $pixel = $this->pixelMaskBase | ($channels['r'] / 8) << 10 | ($channels['g'] / 8) << 5 | ($channels['b'] / 8);

        return $pixel;
    }

    public function setDevice($device)
    {
        $this->channel = $device;

        return $this;
    }

    public function setPixelCount($pixelCount)
    {
        $this->pixelCount = $pixelCount;

        return $this;
    }

    public function writeData($data)
    {
        $this->buffer .= $this->packMultiChar($data);

        return $this;
    }

    public function writePixelStream(PixelStream $stream, $flush = false)
    {
        $this->writeReset();

        foreach ($stream->getPixelArray() as $pixel) {
            $this->writeData($this->processPixel($pixel));
        }

        if (true === $flush) {
            return $this->flush();
        }

        return $this;
    }

    public function writeReset()
    {
        Debug::log('Sending Reset To Device');
        $this->writeData($this->reset);
        return $this;
    }

    protected function checkDevice()
    {
        Debug::log('Checking device: ' . $this->channel);

        if (false === file_exists($this->channel)) {
            $this->loadKernelModule();
        }

        if (false === is_writable($this->channel)) {
            throw new \Exception('The device ' . $this->channel . ' is not writable by the current user');
        }

        return $this;
    }

    protected function loadKernelModule()
    {
        Debug::log('Loading kernel module');

        try {
            shell_exec('gpio load spi > /dev/null 2> /dev/null');

            if (false === file_exists($this->channel)) {
                throw new \Exception('SPI device kernel module could not be loaded');
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An unknown error occurred while attempting to load the SPI kernel module',
                null,
                $e
            );
        }
    }

    protected function packChar($data)
    {
        return pack('C', (int) $data);
    }

    protected function packMultiChar($data)
    {
        return pack('C*', $data);
    }

    protected function pack16($data)
    {
        return pack('n', (int) $data);
    }

    protected function pack32($data)
    {
        return pack('N', (int) $data);
    }

    protected function setup()
    {
        $this->reset = 0x0000 & 0xFFFF;
        return $this->checkDevice();
    }
}

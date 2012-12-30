<?php

namespace BauerBox\Phixel\Driver\LPD6803;

use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Driver\DriverInterface;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;

class LPD6803Driver implements DriverInterface
{
    protected $buffer;
    protected $device;
    protected $pixelCount;
    protected $pixelMaskRed = 0b1111110000000000;
    protected $pixelMaskGreen = 0b1000001111100000;
    protected $pixelMaskBlue = 0b1000000000011111;
    protected $pixelMaskBase = 0b1000000000000000;
    protected $socket;
    protected $reset;

    public function __construct($device = '/dev/spidev0.0', $pixelCount = 25)
    {
        $this->device = $device;
        $this->pixelCount = $pixelCount;

        $this->setup();
    }

    public function closeSocket()
    {
        if (null === $this->socket || false === $this->socket) {
            throw new \Exception('The device socket is not open and therefore cannot be closed');
        }

        if (false === fclose($this->socket)) {
            throw new \Exception('There was an error closing the socket');
        }

        return $this;
    }

    public function flush()
    {
        Debug::log('Flushing buffer to device');

        if ($this->buffer == '') {
            throw new \Exception('There is no data in the buffer to flush');
        }

        $bufferSize = strlen($this->buffer);

        for ($i = 0; $i < $bufferSize; $i += 2) {
            fwrite($this->socket, substr($this->buffer, $i, 2));
        }

        fflush($this->socket);

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
        if (null === $this->device) {
            throw new \Exception('Cannot open socket to null device');
        }

        if (false === $this->socket = fopen($this->device, 'w')) {
            throw new \Exception('Could not open socket to device: ' . $this->device);
        }

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
        $this->device = $device;

        return $this;
    }

    public function setPixelCount($pixelCount)
    {
        $this->pixelCount = $pixelCount;

        return $this;
    }

    public function writeData($data)
    {
        $this->buffer .= $this->pack16($data);

        return $this;
    }

    public function writePixelStream(PixelStream $stream)
    {
        $this->writeReset();

        foreach ($stream->getPixelArray() as $pixel) {
            $this->writeData($this->processPixel($pixel));
        }

        return $this;
    }

    public function writeReset()
    {
        Debug::log('Sending Reset To Device');
        $this->buffer .= $this->reset;

        return $this;
    }

    protected function checkDevice()
    {
        Debug::log('Checking device: ' . $this->device);

        if (false === is_file($this->device)) {
            $this->loadKernelModule();
        }

        if (false === is_writable($this->device)) {
            throw new \Exception('The device ' . $this->device . ' is not writable by the current user');
        }

        return $this;
    }

    protected function loadKernelModule()
    {
        Debug::log('Loading kernel module');

        try {
            $out = shell_exec('gpio load spi > /dev/null 2> /dev/null; echo $?');

            if ($out != '0') {
                throw new \Exception('SPI device kernel module could not be loaded');
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An unknown error occurred while attempting to load the SPI kernel module',
                null,
                null,
                $e
            );
        }
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
        $this->reset = $this->pack32(0x00);

        $this->writeReset();
    }
}

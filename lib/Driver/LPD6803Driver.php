<?php

namespace BauerBox\Phixel\Driver;

use BauerBox\Phixel\Driver\AbstractHybridDriver;
use BauerBox\Phixel\Pixel\Pixel;

class LPD6803Driver extends AbstractHybridDriver
{
    protected $channelSpeed = self::SPEED_16_MHZ;
    protected $pixelMask = 0x8000;

    protected function flushRaw()
    {
        if ($this->buffer == '') {
            throw new \Exception('There is no data in the buffer to flush');
        }

        $bufferSize = strlen($this->buffer);

        for ($i = 0; $i < $bufferSize; $i += 2) {
            fwrite($this->socket, substr($this->buffer, $i, 2));
        }

        fflush($this->socket);

        $this->resetBuffer();
        $this->writeReset();

        return $this;
    }

    protected function flushSpi()
    {
        if ($this->buffer == '') {
            throw new \Exception('There is no data in the buffer to flush');
        }

        $bufferCount = strlen($this->buffer);
        wiringPiSPIDataRW($this->device, $this->buffer, $bufferCount);

        $this->resetBuffer();
        $this->writeReset();

        return $this;
    }

    public function writeReset()
    {
        $this->writeData(0x0000)->writeData(0x0000);
        return $this;
    }

    public function processPixel(Pixel $pixel)
    {
        // Convert compiled color to 15-bit used by LPD6803
        $channels = $pixel->getCompiledColor(true);
        return $this->pixelMask | ($channels['r'] / 8) << 10 | ($channels['g'] / 8) << 5 | ($channels['b'] / 8);
    }

    protected function pack16($data)
    {
        return pack('n', (int) $data);
    }

    protected function pack32($data)
    {
        return pack('N', (int) $data);
    }

    protected function packChar($data)
    {
        return pack('C', (int) $data);
    }

    protected function packMultiChar($data)
    {
        return pack('C*', (int) $data);
    }
}

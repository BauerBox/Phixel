<?php

namespace BauerBox\Phixel\Driver;

use BauerBox\Phixel\Driver\AbstractHybridDriver;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Debug\Debug;

class WS2801Driver extends AbstractHybridDriver
{
    protected $channelSpeed = self::SPEED_4_MHZ;

    protected function flushRaw()
    {
        if ($this->buffer == '') {
            throw new \Exception('There is no data in the buffer to flush');
        }

        $bufferSize = strlen($this->buffer);

        for ($i = 0; $i < $bufferSize; $i += 3) {
            fwrite($this->socket, substr($this->buffer, $i, 3));
        }

        fflush($this->socket);

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

        $this->writeReset();

        return $this;
    }

    public function writeReset()
    {
        usleep(750);
        return $this;
    }

    public function writeData($data)
    {
        Debug::log(sprintf('%06X', $data));

        $this->buffer .= $this->packMultiChar($data & 0xFFFFFF);
        
        return $this;
    }

    public function processPixel(Pixel $pixel)
    {
        return $pixel->getCompiledColor();
    }
}

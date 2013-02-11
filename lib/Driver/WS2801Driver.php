<?php

namespace BauerBox\Phixel\Driver;

use BauerBox\Phixel\Driver\AbstractHybridDriver;

class WS2801Driver extends AbstractHybridDriver
{
    protected $channelSpeed = self::SPEED_16_MHZ;

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
        wiringPiSPIDataRW($this->channel, $this->buffer, $bufferCount);

        $this->resetBuffer();
        $this->writeReset();

        return $this;
    }

    public function writeReset()
    {
        usleep(500);
        return $this;
    }
}

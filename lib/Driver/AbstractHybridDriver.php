<?php

namespace BauerBox\Phixel\Driver;

use BauerBox\Phixel\Driver\DriverInterface;
use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Pixel\PixelStream;
use BauerBox\Phixel\Pixel\Pixel;

abstract class AbstractHybridDriver implements DriverInterface
{
    const MODE_RAW = 0;
    const MODE_SPI = 1;

    const SPEED_500_KHZ =   500000;
    const SPEED_1_MHZ   =   1000000;
    const SPEED_2_MHZ   =   2000000;
    const SPEED_4_MHZ   =   4000000;
    const SPEED_8_MHZ   =   8000000;
    const SPEED_16_MHZ  =   16000000;
    const SPEED_32_MHZ  =   32000000;
    const SPEED_64_MHZ  =   64000000;

    protected $buffer;
    protected $channels = array(
        0   =>  '/dev/spidev0.0',
        1   =>  '/dev/spidev0.1'
    );
    protected $channelSpeed = self::SPEED_2_MHZ;

    protected $device;
    protected $gpioPath;
    protected $kernelModule = 'spi-bcm2708';
    protected $pixelCount;

    protected $mode = self::MODE_RAW;
    protected $modeLocked = false;
    protected $socket;

    public function __construct($pixelCount = 25, $mode = null, $device = 0)
    {
        $this->setPixelCount($pixelCount);
        $this->setMode($mode);
        $this->setDevice($device);
    }

    public function setMode($mode = null)
    {
        if (true === $this->modeLocked) {
            throw new \Exception('The mode can not be changed once the socket has been opened!');
        }

        if (null === $mode) {
            Debug::log('Auto-setting mode');
            if (true === $this->isWiringPiSpiAvailable()) {
                Debug::log('Setting mode to SPI');
                $mode = self::MODE_SPI;
            } else {
                Debug::log('Setting mode to RAW');
                $mode = self::MODE_RAW;
            }
        }

        $this->mode = $mode;
        return $this;
    }

    /* Interface Methods Below Here */
    public function setPixelCount($pixelCount)
    {
        $this->pixelCount = $pixelCount;
        return $this;
    }

    public function getPixelCount()
    {
        return $this->pixelCount;
    }

    final public function setDevice($device)
    {
        if (true === is_int($device)) {
            $this->device = $device;
        } else {
            $this->device = $this->getChannel($device);
        }

        return $this;
    }

    public function openSocket()
    {
        Debug::log('Opening driver socket');

        $this->modeLocked = true;
        $this->checkDevice();

        if ($this->mode === self::MODE_RAW) {
            if (null === $this->device) {
                throw new \Exception('Cannot open socket to null device');
            }

            if (false === $this->socket = fopen($this->getDevice($this->device), 'wb')) {
                throw new \Exception('Could not open socket to device: ' . $this->getDevice($this->device));
            }
        } else {
            if (wiringPiSPISetup($this->device, $this->channelSpeed) < 0) {
                throw new \Exception('There was an error running setup for SPI');
            }
        }

        return $this;
    }

    public function closeSocket()
    {
        Debug::log('Closing driver socket');

        if ($this->mode === self::MODE_RAW) {
            if (null === $this->socket || false === $this->socket) {
                throw new \Exception('The device socket is not open and therefore cannot be closed');
            }

            if (false === fclose($this->socket)) {
                throw new \Exception('There was an error closing the socket');
            }
        }

        $this->modeLocked = false;

        return $this;
    }

    public function writeData($data)
    {
        $this->buffer .= $data;
        return $this;
    }

    final public function flush()
    {
        Debug::log('Flushing buffer to device');

        if ($this->mode === self::MODE_RAW) {
            $this->flushRaw();
        } else {
            $this->flushSpi();
        }

        $this->resetBuffer();
        return $this;
    }

    abstract protected function flushRaw();
    abstract protected function flushSpi();

    public function writePixelStream(PixelStream $stream, $flush = false)
    {
        $this->writeReset();

        $array = $stream->getPixelArray();

        foreach ($array as &$pixel) {
            $this->writeData($this->processPixel($pixel));
        }

        if (true === $flush) {
            return $this->flush();
        }

        return $this;
    }

    public function processPixel(Pixel $pixel)
    {
        return $pixel->getCompiledColor();
    }

    protected function isWiringPiSpiAvailable()
    {
        return (boolean) function_exists('wiringPiSPISetup') && function_exists('wiringPiSetupSys');
    }

    protected function isGpioAvailable()
    {
        if (null === $this->gpioPath) {
            $path = shell_exec('which gpio');

            if (null === $path || $path == '') {
                $this->gpioPath = false;
            } else {
                $this->gpioPath = $path;
            }
        }

        return ($this->gpioPath !== false);
    }

    protected function getDevice($channel)
    {
        if (true === array_key_exists($channel, $this->channels)) {
            return $this->channels[$channel];
        }

        throw new \Exception('Invalid SPI channel: ' . $channel);
    }

    protected function getChannel($device)
    {
        if (true === in_array($device, $this->channels)) {
            return array_search($device, $this->channels);
        }

        throw new \Exception('Invalid SPI Device: ' . $device);
    }

    protected function isKernelModuleLoaded()
    {
        return (boolean) file_exists($this->channels[0]);
    }

    protected function checkDevice()
    {
        $device = $this->getDevice($this->device);
        Debug::log('Checking device: ' . $device . ' (Channel ' . $this->device . ')');

        if ($this->mode === self::MODE_RAW) {
            if (false === file_exists($device)) {
                $this->loadKernelModule();
            }

            if (false === is_writable($device)) {
                throw new \Exception(
                    'The device ' . $device . ' is not writable by the current user'
                );
            }
        } else {
            if (false === $this->isWiringPiSpiAvailable()) {
                throw new \Exception('To use SPI mode, you must enable the wiringPi extension with SPI bindings');
            }

            Debug::log('WiringPi Sys Setup: ' . wiringPiSetupSys());
        }

        return $this;
    }

    protected function loadKernelModule()
    {
        Debug::log('Attempting to load kernel module');

        if ($this->isGpioAvailable()) {
            try {
                shell_exec($this->gpioPath . ' load spi > /dev/null 2> /dev/null');
            } catch (\Exception $e) {
                throw new \Exception(
                    'Unable to load kernel module using WiringPi gpio',
                    null,
                    $e
                );
            }
        } else {
            if (true === $this->isSuperUser()) {
                try {
                    shell_exec('modprobe ' . $this->kernelModule);
                } catch (\Exception $e) {
                    throw new \Exception(
                        'Unable to load kernel module using modprobe',
                        null,
                        $e
                    );
                }
            } else {
                throw new \Exception('This script must be run with root privileges in order to load SPI driver');
            }
        }

        if (false === file_exists($this->getDevice($this->device))) {
            throw new \Exception('An unknown error occured while attempting to load SPI kernel module');
        }
    }

    protected function isSuperUser()
    {
        return (trim(shell_exec('id -u')) == '0');
    }

    protected function initializeBuffer()
    {
        Debug::log('Initializing local buffer');
        return $this->resetBuffer();
    }

    protected function resetBuffer()
    {
        $this->buffer = '';
        return $this;
    }

    /* Binary Helpers */
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

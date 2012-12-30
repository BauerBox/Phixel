<?php

namespace BauerBox\Phixel;

use BauerBox\Phixel\Driver\DriverInterface;
use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;

class Phixel
{
    public static $classBase = 'BauerBox\\Phixel\\';
    protected static $maxBrightness = 1.0;

    /**
     * The driver object for interfacing with the pixel string connected to the SPI device
     *
     * @var \BauerBox\Phixel\Driver\DriverInterface
     */
    protected $driver;

    public function __construct(DriverInterface $driver, $device = null)
    {
        $this->driver = $driver;

        if (null !== $device) {
            $this->driver->setDevice($device);
        }

        $this->driver->openSocket();
    }

    public function __destruct()
    {
        $this->driver->closeSocket();
    }

    public function allOn()
    {
        $this->driver->writePixelStream(new PixelStream($this->driver->getPixelCount(), 0xffffff))->flush();

        return $this;
    }

    public function allOff()
    {
        $this->driver->writePixelStream(new PixelStream($this->driver->getPixelCount(), 0x000000))->flush();

        return $this;
    }

    public function fill($color = 0xffffff)
    {
        $this->driver->writePixelStream(new PixelStream($this->driver->getPixelCount(), $color))->flush();
    }

    public function chase()
    {
        $stream = new PixelStream($this->driver->getPixelCount(), 0x000000);

        $colors = array(
            0x880000,
            0x008800,
            0x000088,
            0xff0000,
            0x00ff00,
            0x0000ff
        );

        $max = $this->driver->getPixelCount();

        foreach ($colors as $color) {
            for ($i = 0; $i < $max; ++$i) {
                $pixel = $stream->getPixel($i);
                $pixel->setColor($color);
                $this->driver->writePixelStream($stream)->flush();
                usleep(100);
            }

            for ($i = 0; $i < $max; ++$i) {
                $pixel = $stream->getPixel($i);
                $pixel->setColor(0x000000);
                $this->driver->writePixelStream($stream)->flush();
                usleep(100);
            }
        }
    }

    public function getPixelCount()
    {
        return $this->driver->getPixelCount();
    }

    public function getNewPixel($color = 0xffffff, $brightness = 1.0)
    {
        return new Pixel($color, $brightness);
    }

    // ALL STATIC BELOW HERE

    public static function installAutloader()
    {
        spl_autoload_register('BauerBox\Phixel\Phixel::autoload');
    }

    public static function autoload($class)
    {
        if (0 !== strpos($class, static::$classBase)) {
            return;
        }

        $class = str_replace(array(static::$classBase, '\\'), array('', '/'), $class);

        if (is_file($file = dirname(__FILE__).'/'.str_replace(array('_', "\0"), array('/', ''), $class).'.php')) {
            require $file;
            Debug::log('Loaded file: ' . $file);
        } else {
            Debug::log('Could not find file: ' . $file);
        }
    }

    public static function enableDebugOutput()
    {
        Debug::enable();
    }

    public static function getMaxBrightness()
    {
        return static::$maxBrightness;
    }

    public static function setMaxBrightness($brightness)
    {
        if ($brightness > 1.0 || $brightness < 0.0) {
            throw new \Exception('Brightness out of range: ' . $brightness);
        }

        static::$maxBrightness = $brightness;
    }
}

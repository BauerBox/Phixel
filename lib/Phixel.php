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
        usleep(100);

        return $this;
    }

    public function allOff()
    {
        $this->driver->writePixelStream(new PixelStream($this->driver->getPixelCount(), 0x000000))->flush();
        usleep(100);

        return $this;
    }

    public function fill($color = 0xffffff)
    {
        $this->driver->writePixelStream(new PixelStream($this->driver->getPixelCount(), $color))->flush();
        usleep(100);

        return $this;
    }

    public function chase($color = 0xffffff)
    {
        $stream = new PixelStream($this->driver->getPixelCount(), 0x000000);

        for ($i = 0; $i < $max; ++$i) {
            $pixel = $stream->getPixel($i);
            $pixel->setColor($color);
            $this->driver->writePixelStream($stream)->flush();
        }
    }

    /**
     * Gets the driver instance
     *
     * @return \BauerBox\Phixel\Driver\DriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    public function getPixelCount()
    {
        return $this->driver->getPixelCount();
    }

    /**
     *
     * @param int $color
     * @param float $brightness
     * @return \BauerBox\Phixel\Pixel\Pixel
     */
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

        if (false !== $file = static::getFilePath($class)) {
            require $file;
        }
    }

    public static function enableDebugOutput()
    {
        Debug::enable();
    }

    public static function getFilePath($shortcut, $extension = 'php')
    {
        if (is_file($file = dirname(__FILE__).'/'.str_replace(array('_', ':', "\0"), array('/', '/', ''), $shortcut).'.'.$extension)) {
            return $file;
        }

        return false;
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

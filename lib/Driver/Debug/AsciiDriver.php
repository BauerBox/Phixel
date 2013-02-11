<?php

namespace BauerBox\Phixel\Driver\Debug;

use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Driver\DriverInterface;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;
use BauerBox\Phixel\Phixel;

class AsciiDriver implements DriverInterface
{
    protected $pixelCount;

    protected $buffer;
    protected $columnBuffer;
    protected $frame;
    protected $tick;
    protected $columns;
    protected $rows;
    protected $clearOnReset;

    public function __construct($tick = 500000)
    {
        $this->tick = $tick;
        $this->frame = 0;
        $this->clearOnReset = false;
    }

    public function closeSocket()
    {
        Debug::log('Ending Driver Session');
        return $this;
    }

    public function flush()
    {
        Debug::log('Printing Buffer');

        ++$this->frame;

        $indent = '      ';
        echo $indent;
        $temp = $indent;

        foreach ($this->columnBuffer as $columnIndex => $columnData) {
            echo sprintf(' %03d ', $columnIndex);
            $temp .= ' --- ';
        }

        echo PHP_EOL . $temp . PHP_EOL;

        for ($row = 0; $row < $this->rows; ++$row) {
            echo sprintf('%03d | ', $row);

            foreach ($this->columnBuffer as $columnIndex => $columnData) {
                echo sprintf('[%3s]', str_repeat($this->buffer[$columnData[$row]], 3));
            }
            echo PHP_EOL;
        }
        echo "Frame: {$this->frame}" . PHP_EOL;
        usleep($this->tick);
        return $this;
    }

    public function getPixelCount()
    {
        return $this->pixelCount;
    }

    public function openSocket()
    {
        Debug::log('Starting Driver Session');

        return $this;
    }

    public function processPixel(Pixel $pixel)
    {
        $channels = $pixel->getChannelValues();

        // Get relative brightness (since ASCII is monochromatic)
        $rel = (($channels['r'] + $channels['g'] + $channels['b']) / 3);

        if ($rel >= 0xFF / 2) {
            return '#';
        } elseif ($rel > 0xFF / 4) {
            return '*';
        } elseif ($rel > 0xFF / 8) {
            return '+';
        } elseif ($rel > 0x00) {
            return '.';
        }

        return ' ';
    }

    public function setDevice($device)
    {
        return $this;
    }

    public function setClearOnReset($clear = true)
    {
        $this->clearOnReset = (boolean) $clear;
        return $this;
    }

    public function setMatrixSize($columns = 5, $rows = 5)
    {
        $this->columns = $columns;
        $this->rows = $rows;
        $this->setPixelCount($columns * $rows);

        $this->buildMatrix();

        return $this;
    }

    public function setPixelCount($pixelCount)
    {
        $this->pixelCount = $pixelCount;
        return $this;
    }

    public function writeData($data)
    {
        Debug("Writing Data???  Hmmm....");
        return $this;
    }

    public function writePixelStream(PixelStream $stream)
    {
        $this->writeReset();

        foreach ($stream->getPixelArray() as $index => $pixel) {
            $this->buffer[$index] = $this->processPixel($pixel);
        }

        return $this;
    }

    public function writeReset()
    {
        if (true === $this->clearOnReset) {
            Debug::log('Clearing the screen');
            echo $this->cls;
        }

        return $this;
    }

    protected function buildMatrix()
    {
        $this->columnBuffer = array();

        for ($col = 0; $col < $this->columns; ++$col) {
            $current = array();

            $colMin = $col * $this->rows;
            $colMax = $colMin + ($this->rows - 1);

            if ($col % 2 == 0) {
                // Bottom to Top
                for ($i = $colMax; $i >= $colMin; --$i) {
                    $current[] = $i;
                }
            } else {
                // Top to Bottom
                for ($i = $colMin; $i <= $colMax; ++$i) {
                    $current[] = $i;
                }
            }

            $this->columnBuffer[$col] = $current;
        }

        return $this;
    }
}

<?php

namespace BauerBox\Phixel\Driver\Debug;

use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Driver\DriverInterface;
use BauerBox\Phixel\Pixel\Pixel;
use BauerBox\Phixel\Pixel\PixelStream;
use BauerBox\Phixel\Driver\Debug\AsciiDriver;

class AnsiDriver extends AsciiDriver
{
    protected $pixelCount;

    protected $buffer;
    protected $columnBuffer;
    protected $frame;
    protected $tick;
    protected $columns;
    protected $rows;
    protected $clearOnReset;

    protected $backgroundColors = array(
		'default'	=>	49,
		'black'		=>	40,
		'red'		=>	41,
		'green'		=>	42,
		'yellow'	=>	43,
		'blue'		=>	44,
		'magenta'	=>	45,
		'cyan'		=>	46,
		'white'		=>	47
	);

    protected $xtermColors = array(
		'000000'=>16,'00005F'=>17,'000087'=>18,'0000AF'=>19,'0000D7'=>20,
		'0000FF'=>21,'005F00'=>22,'005F5F'=>23,'005F87'=>24,'005FAF'=>25,
		'005FD7'=>26,'005FFF'=>27,'008700'=>28,'00875F'=>29,'008787'=>30,
		'0087AF'=>31,'0087D7'=>32,'0087FF'=>33,'00AF00'=>34,'00AF5F'=>35,
		'00AF87'=>36,'00AFAF'=>37,'00AFD7'=>38,'00AFFF'=>39,'00D700'=>40,
		'00D75F'=>41,'00D787'=>42,'00D7AF'=>43,'00D7D7'=>44,'00D7FF'=>45,
		'00FF00'=>46,'00FF5F'=>47,'00FF87'=>48,'00FFAF'=>49,'00FFD7'=>50,
		'00FFFF'=>51,'5F0000'=>52,'5F005F'=>53,'5F0087'=>54,'5F00AF'=>55,
		'5F00D7'=>56,'5F00FF'=>57,'5F5F00'=>58,'5F5F5F'=>59,'5F5F87'=>60,
		'5F5FAF'=>61,'5F5FD7'=>62,'5F5FFF'=>63,'5F8700'=>64,'5F875F'=>65,
		'5F8787'=>66,'5F87AF'=>67,'5F87D7'=>68,'5F87FF'=>69,'5FAF00'=>70,
		'5FAF5F'=>71,'5FAF87'=>72,'5FAFAF'=>73,'5FAFD7'=>74,'5FAFFF'=>75,
		'5FD700'=>76,'5FD75F'=>77,'5FD787'=>78,'5FD7AF'=>79,'5FD7D7'=>80,
		'5FD7FF'=>81,'5FFF00'=>82,'5FFF5F'=>83,'5FFF87'=>84,'5FFFAF'=>85,
		'5FFFD7'=>86,'5FFFFF'=>87,'870000'=>88,'87005F'=>89,'870087'=>90,
		'8700AF'=>91,'8700D7'=>92,'8700FF'=>93,'875F00'=>94,'875F5F'=>95,
		'875F87'=>96,'875FAF'=>97,'875FD7'=>98,'875FFF'=>99,'878700'=>100,
		'87875F'=>101,'878787'=>102,'8787AF'=>103,'8787D7'=>104,'8787FF'=>105,
		'87AF00'=>106,'87AF5F'=>107,'87AF87'=>108,'87AFAF'=>109,'87AFD7'=>110,
		'87AFFF'=>111,'87D700'=>112,'87D75F'=>113,'87D787'=>114,'87D7AF'=>115,
		'87D7D7'=>116,'87D7FF'=>117,'87FF00'=>118,'87FF5F'=>119,'87FF87'=>120,
		'87FFAF'=>121,'87FFD7'=>122,'87FFFF'=>123,'AF0000'=>124,'AF005F'=>125,
		'AF0087'=>126,'AF00AF'=>127,'AF00D7'=>128,'AF00FF'=>129,'AF5F00'=>130,
		'AF5F5F'=>131,'AF5F87'=>132,'AF5FAF'=>133,'AF5FD7'=>134,'AF5FFF'=>135,
		'AF8700'=>136,'AF875F'=>137,'AF8787'=>138,'AF87AF'=>139,'AF87D7'=>140,
		'AF87FF'=>141,'AFAF00'=>142,'AFAF5F'=>143,'AFAF87'=>144,'AFAFAF'=>145,
		'AFAFD7'=>146,'AFAFFF'=>147,'AFD700'=>148,'AFD75F'=>149,'AFD787'=>150,
		'AFD7AF'=>151,'AFD7D7'=>152,'AFD7FF'=>153,'AFFF00'=>154,'AFFF5F'=>155,
		'AFFF87'=>156,'AFFFAF'=>157,'AFFFD7'=>158,'AFFFFF'=>159,'D70000'=>160,
		'D7005F'=>161,'D70087'=>162,'D700AF'=>163,'D700D7'=>164,'D700FF'=>165,
		'D75F00'=>166,'D75F5F'=>167,'D75F87'=>168,'D75FAF'=>169,'D75FD7'=>170,
		'D75FFF'=>171,'D78700'=>172,'D7875F'=>173,'D78787'=>174,'D787AF'=>175,
		'D787D7'=>176,'D787FF'=>177,'D7AF00'=>178,'D7AF5F'=>179,'D7AF87'=>180,
		'D7AFAF'=>181,'D7AFD7'=>182,'D7AFFF'=>183,'D7D700'=>184,'D7D75F'=>185,
		'D7D787'=>186,'D7D7AF'=>187,'D7D7D7'=>188,'D7D7FF'=>189,'D7FF00'=>190,
		'D7FF5F'=>191,'D7FF87'=>192,'D7FFAF'=>193,'D7FFD7'=>194,'D7FFFF'=>195,
		'FF0000'=>196,'FF005F'=>197,'FF0087'=>198,'FF00AF'=>199,'FF00D7'=>200,
		'FF00FF'=>201,'FF5F00'=>202,'FF5F5F'=>203,'FF5F87'=>204,'FF5FAF'=>205,
		'FF5FD7'=>206,'FF5FFF'=>207,'FF8700'=>208,'FF875F'=>209,'FF8787'=>210,
		'FF87AF'=>211,'FF87D7'=>212,'FF87FF'=>213,'FFAF00'=>214,'FFAF5F'=>215,
		'FFAF87'=>216,'FFAFAF'=>217,'FFAFD7'=>218,'FFAFFF'=>219,'FFD700'=>220,
		'FFD75F'=>221,'FFD787'=>222,'FFD7AF'=>223,'FFD7D7'=>224,'FFD7FF'=>225,
		'FFFF00'=>226,'FFFF5F'=>227,'FFFF87'=>228,'FFFFAF'=>229,'FFFFD7'=>230,
		'FFFFFF'=>231,'080808'=>232,'121212'=>233,'1C1C1C'=>234,'262626'=>235,
		'303030'=>236,'3A3A3A'=>237,'444444'=>238,'4E4E4E'=>239,'585858'=>240,
		'626262'=>241,'6C6C6C'=>242,'767676'=>243,'808080'=>244,'8A8A8A'=>245,
		'949494'=>246,'9E9E9E'=>247,'A8A8A8'=>248,'B2B2B2'=>249,'BCBCBC'=>250,
		'C6C6C6'=>251,'D0D0D0'=>252,'DADADA'=>253,'E4E4E4'=>254,'EEEEEE'=>255
	);
    protected $escapeFormat = "\033[%sm";
	protected $reset = "\033[0m";
	protected $cls = "\033[2J";

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

        echo '+' . str_repeat('--', $this->columns) . '+' . PHP_EOL;

        for ($row = 0; $row < $this->rows; ++$row) {
            echo '|';
            foreach ($this->columnBuffer as $columnData) {
                echo $this->buffer[$columnData[$row]];
            }
            echo '|';
            echo PHP_EOL;
        }
        echo '+' . str_repeat('--', $this->columns) . '+' . PHP_EOL;
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
        // Sniff for black
        Debug::log($pixel->getBrightness());
        if ($pixel->getBrightness() < 0.2) {
            return '  ';
        }

        $color = sprintf(
            $this->escapeFormat,
            '48;5;' .
            $this->closestXtermColor(
                sprintf(
                    '%06X',
                    $pixel->getCompiledColor()
                ),
                true
            )
        );

        return $color . '  ' . sprintf($this->escapeFormat, 0);
    }

    public function setDevice($device)
    {
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

    /**
	 * Convert an RGB hex color to the closest xterm equivilent
	 *
	 * @param string $rgbString The hex string to convert
	 * @param boolean $foreground Whether this is for a foreground or background color
	 *
	 * @return integer
	 */
	protected function closestXtermColor($rgbString, $foreground = true)
    {
		// Replace # sign if there
		$rgbString = str_replace('#', '', strtoupper($rgbString));

		// Check the length
		if (strlen($rgbString) != 6) {
			if ($foreground) {
                return 231; // White
            }
			return 16; // Black
		}

		// Breakout the RGB colors
		$r = hexdec(substr($rgbString, 0, 2));
		$g = hexdec(substr($rgbString, 2, 2));
		$b = hexdec(substr($rgbString, 4, 2));

		// Check for Greyscale color
		if ($r == $g && $g == $b || $this->isAlmostGrey($r, $g, $b)) {
			$g = $this->closestXtermGrey(max($r, $g, $b));
			$color = $g.$g.$g;
		} else {
			// Color
			$color = $this->closestXtermOctet($r).$this->closestXtermOctet($g).$this->closestXtermOctet($b);
		}

		return $this->xtermColors[$color];
	}

	/**
	 * Convert a hex octet to the closest grey octet used for xterm colors
	 *
	 * @param integer $g The integer value of the hex to be converted
	 *
	 * @return string
	 */
	protected function closestXtermGrey($g = 0)
    {
		if ($g < 4) {
            return '00';
        }

		if ($g > 243) {
            return 'FF';
        };

		$m = $g % 10;

		if ($m != 8) {
			if ($m > 3 && $m < 8) {
				$g = $g + (8 - $m);
			} else {
				switch ($m) {
					case 3:
						$g--;
                        // Fall Thru
					case 2:
						$g--;
                        // Fall Thru
					case 1:
						$g--;
                        // Fall Thru
					case 0:
						$g--;
                        // Fall Thru
					case 9:
						$g--;
                        // Fall Thru
				}
			}
		}

		unset($m);
		$h = strtoupper(dechex($g));
		unset($g);

		if (strlen($h) == 1) {
			return '0'.$h;
		} else {
			return $h;
		}
	}

	/**
	 * Get the closest xterm color octet for the octet supplied
	 *
	 * @param integer $c
	 *
	 * @return string
	 */
	protected function closestXtermOctet($c = 0)
    {
		if ($c >= 0 && $c < 47) {
			return '00';
		} elseif ($c > 46 && $c < 116) {
			return '5F';
		} elseif ($c > 115 && $c < 156) {
			return '87';
		} elseif ($c > 155 && $c < 196) {
			return 'AF';
		} elseif ($c > 195 && $c < 236) {
			return 'D7';
		} else {
			return 'FF';
		}
	}

    /**
	 * Determines if the color passed is almost a grey
	 *
	 * This function determines if the 3 octets that make up an RGB color are closes
	 * enough to each other to be rendered as a grey.  This prevents tinted greys from
	 * being rendered as black when using xterm colors
	 *
	 * @param integer $r
	 * @param integer $g
	 * @param integer $b
	 */
	protected function isAlmostGrey($r, $g, $b)
    {
		// Get the smallest value of the colors passed
		$min = min($r, $g, $b);

		// Subtract the min value from all colors
		$r -= $min;
		$g -= $min;
		$b -= $min;

		// Now check the max against out threshhold
		if (max($r, $g, $b) < 24) {
			return true;
		}
		return false;
	}
}

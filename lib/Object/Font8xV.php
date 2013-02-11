<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\AbstractObject;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Color\Wheel;

class Font8xV extends AbstractObject
{
    public static $letters = array(
        ' ' =>  array(
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' '
        ),
        'A' =>  array(
            '+##+',
            '#  #',
            '#  #',
            '####',
            '#  #',
            '#  #',
            '#  #',
            '#  #'
        ),
        'B' =>  array(
            '###+',
            '#  #',
            '#  *',
            '### ',
            '#  *',
            '#  #',
            '#  #',
            '###+'
        ),
        'C' =>  array(
            '-##-',
            '#  #',
            '#  -',
            '#   ',
            '#   ',
            '#  -',
            '#  #',
            '-##-'
        ),
        'D' =>  array(
            '###+',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '###+'
        ),
        'E' =>  array(
            '###*',
            '#   ',
            '#   ',
            '##+ ',
            '#   ',
            '#   ',
            '#   ',
            '###*'
        ),
        'F' =>  array(
            '###*',
            '#   ',
            '#   ',
            '##+ ',
            '#   ',
            '#   ',
            '#   ',
            '#   '
        ),
        'G' =>  array(
            '+##+',
            '#  #',
            '#   ',
            '# +#',
            '#  #',
            '#  #',
            '#  #',
            '+##+'
        ),
        'H' =>  array(
            '#  #',
            '#  #',
            '#  #',
            '####',
            '#  #',
            '#  #',
            '#  #',
            '#  #'
        ),
        'I' =>  array(
            '*#*',
            ' # ',
            ' # ',
            ' # ',
            ' # ',
            ' # ',
            ' # ',
            '*#*'
        ),
        'J' =>  array(
            ' *#*',
            '   #',
            '   #',
            '   #',
            '   #',
            '   #',
            '*  #',
            ' ## '
        ),
        'K' =>  array(
            '*  *',
            '#  #',
            '# # ',
            '##  ',
            '# # ',
            '#  #',
            '#  #',
            '#  #'
        ),
        'L' =>  array(
            '#   ',
            '#   ',
            '#   ',
            '#   ',
            '#   ',
            '#   ',
            '#   ',
            '####'
        ),
        'M' =>  array(
            '*# #*',
            '# # #',
            '# # #',
            '# + #',
            '#   #',
            '#   #',
            '#   #',
            '#   #'
        ),
        'N' =>  array(
            '*   *',
            '#   #',
            '#*  #',
            '# # #',
            '# # #',
            '#  *#',
            '#   #',
            '#   #'
        ),
        'O' =>  array(
            '+###+',
            '#   #',
            '#   #',
            '#   #',
            '#   #',
            '#   #',
            '#   #',
            '+###+'
        ),
        'P' =>  array(
            '###+',
            '#  #',
            '#  #',
            '###+',
            '#   ',
            '#   ',
            '#   ',
            '#   '
        ),
        'Q' =>  array(
            '+###+',
            '#   #',
            '#   #',
            '#   #',
            '#   #',
            '# + #',
            '+### ',
            '    #'
        ),
        'R' =>  array(
            '###+',
            '#  #',
            '#  *',
            '##* ',
            '#  *',
            '#  #',
            '#  #',
            '#  #'
        ),
        'S' =>  array(
            '+##-',
            '#  *',
            '#  -',
            ' #  ',
            '  # ',
            '-  #',
            '*  #',
            '-##+'
        ),
        'T' =>  array(
            '*###*',
            '  #  ',
            '  #  ',
            '  #  ',
            '  #  ',
            '  #  ',
            '  #  ',
            '  #  '
        ),
        'U' =>  array(
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '+##+'
        ),
        'V' =>  array(
            '#   #',
            '#   #',
            '#   #',
            '*   *',
            ' # # ',
            ' # # ',
            ' # # ',
            '  #  '
        ),
        'W' =>  array(
            '#   #',
            '#   #',
            '#   #',
            '#   #',
            '# * #',
            '# # #',
            '# # #',
            '+###+'
        ),
        'X' =>  array(
            '#   #',
            ' # # ',
            ' # # ',
            '  #  ',
            '  #  ',
            ' # # ',
            ' # # ',
            '#   #'
        ),
        'Y' =>  array(
            '#   #',
            '#   #',
            ' * * ',
            ' # # ',
            '  #  ',
            '  #  ',
            '  #  ',
            '  #  '
        ),
        'Z' =>  array(
            '###*',
            '   #',
            '  # ',
            '  # ',
            ' #  ',
            ' #  ',
            '#   ',
            '*###'
        ),
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        'a' =>  array(
            '    ',
            '    ',
            '    ',
            '*###',
            '   #',
            '+###',
            '#  #',
            '+##+'
        ),
        'b' =>  array(
            '    ',
            '*   ',
            '#   ',
            '#   ',
            '###+',
            '#  #',
            '#  #',
            '###+'
        ),
        'c' =>  array(
            '    ',
            '    ',
            '    ',
            ' ## ',
            '#  *',
            '#   ',
            '#  *',
            ' ## '
        ),
        'd' =>  array(
            '    ',
            '   *',
            '   #',
            '   #',
            '+###',
            '#  #',
            '#  #',
            '+###'
        ),
        'e' =>  array(
            '    ',
            '    ',
            '    ',
            '+##+',
            '#  #',
            '###+',
            '#   ',
            '+##*'
        ),
        'f' =>  array(
            '    ',
            ' +#-',
            ' * +',
            ' #  ',
            '*##*',
            ' #  ',
            ' #  ',
            ' #  '
        ),
        'g' =>  array(
            '    ',
            '    ',
            '    ',
            '+##+',
            '#  #',
            '+###',
            '   #',
            '-*##'
        ),
        'h' =>  array(
            '    ',
            '*   ',
            '#   ',
            '###+',
            '#  #',
            '#  #',
            '#  #',
            '#  #'
        ),
        'i' =>  array(
            ' ',
            ' ',
            '*',
            ' ',
            '*',
            '#',
            '#',
            '#'
        ),
        'j' =>  array(
            '   ',
            '   ',
            '  *',
            '   ',
            '  *',
            '  #',
            '* #',
            ' ##'
        ),
        'k' =>  array(
            '    ',
            '*   ',
            '#  *',
            '# # ',
            '##  ',
            '# # ',
            '#  #',
            '#  #'
        ),
        'l' =>  array(
            '  ',
            '* ',
            '# ',
            '# ',
            '# ',
            '# ',
            '# ',
            '#*'
        ),
        'm' =>  array(
            '     ',
            '     ',
            '     ',
            '-* *-',
            '# # #',
            '# # #',
            '# + #',
            '#   #'
        ),
        'n' =>  array(
            '    ',
            '    ',
            '    ',
            '-**-',
            '#  #',
            '#  #',
            '#  #',
            '#  #'
        ),
        'o' =>  array(
            '    ',
            '    ',
            '    ',
            '+##+',
            '#  #',
            '#  #',
            '#  #',
            '+##+'
        ),
        'p' =>  array(
            '    ',
            '    ',
            '    ',
            '*##+',
            '#  #',
            '###+',
            '#   ',
            '#   '
        ),
        'q' =>  array(
            '     ',
            '     ',
            '     ',
            '+##+ ',
            '#  # ',
            '+### ',
            '   # ',
            '   #*'
        ),
        'r' =>  array(
            '    ',
            '    ',
            '    ',
            ' -#*',
            ' #  ',
            ' #  ',
            ' #  ',
            '*#*+ '
        ),
        's' =>  array(
            '    ',
            '    ',
            '    ',
            '+##-',
            '#   ',
            '-##-',
            '   #',
            '-##+'
        ),
        't' =>  array(
            '   ',
            ' * ',
            ' # ',
            '*#*',
            ' # ',
            ' # ',
            ' # ',
            ' +#'
        ),
        'u' =>  array(
            '    ',
            '    ',
            '    ',
            '*  *',
            '#  #',
            '#  #',
            '#  #',
            '+##+'
        ),
        'v' =>  array(
            '     ',
            '     ',
            '     ',
            '*   *',
            '#   #',
            ' # # ',
            ' # # ',
            '  #  '
        ),
        'w' =>  array(
            '     ',
            '     ',
            '     ',
            '*   *',
            '# + #',
            '# * #',
            '# # #',
            '+###+'
        ),
        'x' =>  array(
            '     ',
            '     ',
            '     ',
            '*   *',
            ' # # ',
            '  #  ',
            ' # # ',
            '#   #'
        ),
        'y' =>  array(
            '     ',
            '     ',
            '     ',
            '*   *',
            '#   #',
            ' # # ',
            '  #  ',
            '  #  '
        ),
        'z' =>  array(
            '    ',
            '    ',
            '    ',
            '*##*',
            '   #',
            '  # ',
            ' #  ',
            '*##*'
        ),
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        '0' =>  array(
            '+##+',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '#  #',
            '+##+'
        ),
        '1' =>  array(
            ' # ',
            '*# ',
            ' # ',
            ' # ',
            ' # ',
            ' # ',
            ' # ',
            '###'
        ),
        '2' =>  array(
            ' ## ',
            '+  #',
            '   #',
            '  # ',
            ' #  ',
            ' #  ',
            '#   ',
            '####'
        ),
        '3' =>  array(
            ' ## ',
            '+  #',
            '   #',
            ' +# ',
            '   #',
            '   #',
            '+  #',
            ' ## '
        ),
        '4' =>  array(
            '   #',
            '  ##',
            ' # #',
            '#  #',
            '####',
            '   #',
            '   #',
            '   #'
        ),
        '5' =>  array(
            '####',
            '#   ',
            '#   ',
            ' ## ',
            '   #',
            '   #',
            '+  #',
            ' ## '
        ),
        '6' =>  array(
            ' ###',
            '#   ',
            '#   ',
            '### ',
            '#  #',
            '#  #',
            '#  #',
            ' ## '
        ),
        '7' =>  array(
            '####',
            '   #',
            '  # ',
            '  # ',
            ' #  ',
            ' #  ',
            '#   ',
            '#   '
        ),
        '8' =>  array(
            ' ## ',
            '#  #',
            '#  #',
            ' ## ',
            '#  #',
            '#  #',
            '#  #',
            ' ## '
        ),
        '9' =>  array(
            ' ## ',
            '#  #',
            '#  #',
            ' ###',
            '   #',
            '   #',
            '   #',
            '   # '
        ),
        '.' =>  array(
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            '#',
            ' '
        ),
        '!' =>  array(
            '#',
            '#',
            '#',
            '#',
            '*',
            '-',
            ' ',
            '#'
        ),
        ':' =>  array(
            ' ',
            '#',
            ' ',
            ' ',
            ' ',
            ' ',
            '#',
            ' '
        ),
        ';' =>  array(
            '  ',
            ' #',
            '  ',
            '  ',
            '  ',
            '  ',
            ' #',
            '* '
        ),
        '?' =>  array(
            ' ## ',
            '#  #',
            '*  #',
            '  # ',
            ' #  ',
            ' *  ',
            '    ',
            ' #  '
        ),
        '@' =>  array(
            '+###+',
            '#   #',
            '# +##',
            '# # #',
            '# # #',
            '# ## ',
            '#    ',
            '+##*+'
        ),
        '#' =>  array(
            '     ',
            ' + + ',
            '+*#*+',
            ' # # ',
            '+*#*+',
            ' + + ',
            '     ',
            '     '
        ),
        '$' =>  array(
            '  *  ',
            ' ###+',
            '# *  ',
            ' ### ',
            '  * #',
            '+ * #',
            ' ### ',
            '  *  '
        ),
        '%' =>  array(
            '#- *',
            '-  #',
            '  # ',
            '  # ',
            ' #  ',
            ' #  ',
            '#  -',
            '# -#'
        ),
        '^' =>  array(
            ' * ',
            '# #',
            '   ',
            '   ',
            '   ',
            '   ',
            '   ',
            '   '
        ),
        '&' =>  array(
            '     ',
            '     ',
            '  +  ',
            '  #  ',
            '+###+',
            '# #  ',
            ' ##  ',
            '  #  '
        ),
        '*' =>  array(
            '     ',
            '  *  ',
            '* # *',
            ' #-# ',
            '+-#-+',
            ' #-# ',
            '* # *',
            '  *  '
        ),
        '(' =>  array(
            '  #',
            ' # ',
            '#  ',
            '#  ',
            '#  ',
            '#  ',
            ' # ',
            '  #'
        ),
        ')' =>  array(
            '#  ',
            ' # ',
            '  #',
            '  #',
            '  #',
            '  #',
            ' # ',
            '#  '
        ),
        '[' =>  array(
            '##*',
            '#  ',
            '#  ',
            '#  ',
            '#  ',
            '#  ',
            '#  ',
            '##*'
        ),
        ']' =>  array(
            '*##',
            '  #',
            '  #',
            '  #',
            '  #',
            '  #',
            '  #',
            '*##'
        ),
        '{' =>  array(
            '  *',
            ' # ',
            ' # ',
            '#  ',
            ' # ',
            ' # ',
            ' # ',
            '  *'
        ),
        '}' =>  array(
            '*  ',
            ' # ',
            ' # ',
            '  #',
            ' # ',
            ' # ',
            ' # ',
            '*  '
        ),
        '\'' =>  array(
            ' #',
            '* ',
            '  ',
            '  ',
            '  ',
            '  ',
            '  ',
            '  '
        ),
        '"' =>  array(
            ' #  #',
            '*  * ',
            '     ',
            '     ',
            '     ',
            '     ',
            '     ',
            '     '
        ),
        '<' =>  array(
            '   *',
            '  # ',
            ' #  ',
            '#   ',
            '#   ',
            ' #  ',
            '  # ',
            '   *'
        ),
        '>' =>  array(
            '*   ',
            ' #  ',
            '  # ',
            '   #',
            '   #',
            '  # ',
            ' #  ',
            '*   '
        ),
        ',' =>  array(
            '  ',
            '  ',
            '  ',
            '  ',
            '  ',
            '  ',
            ' #',
            '* '
        ),
        '/' =>  array(
            '   *',
            '   #',
            '  # ',
            '  # ',
            ' #  ',
            ' #  ',
            '#   ',
            '*   '
        ),
        '\\' =>  array(
            '*   ',
            '#   ',
            ' #  ',
            ' #  ',
            '  # ',
            '  # ',
            '   #',
            '   *'
        ),
        '|' =>  array(
            '*',
            '#',
            '#',
            '#',
            '#',
            '#',
            '#',
            '*'
        ),
        '+' =>  array(
            '     ',
            '  *  ',
            '  #  ',
            '*###*',
            '  #  ',
            '  *  ',
            '     ',
            '     '
        ),
        '-' =>  array(
            '   ',
            '   ',
            '   ',
            '*#*',
            '   ',
            '   ',
            '   ',
            '   '
        ),
        '_' =>  array(
            '   ',
            '   ',
            '   ',
            '   ',
            '   ',
            '   ',
            '   ',
            '###'
        ),
        '~' =>  array(
            '     ',
            '     ',
            '    *',
            ' ### ',
            '*    ',
            '     ',
            '     ',
            '     '
        ),
        '`' =>  array(
            '# ',
            ' *',
            '  ',
            '  ',
            '  ',
            '  ',
            '  ',
            '  '
        ),
        'PADDING' => array(
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            ' '
        )
    );

    protected $columns;
    protected $strings;

    protected $color;
    protected $colors;
    protected $loop;
    protected $antialias;

    protected $currentString;

    protected $columnBuffer;

    public function __construct($columns = 24, $loop = true, array $strings = array())
    {
        $this->strings = $strings;
        $this->loop = (boolean) $loop;
        $this->setColumns($columns);
        $this->color = null;
        $this->colors = array();
        $this->antialias = false;
    }

    public function setColors($colors)
    {
        if (true === is_array($colors)) {
            $this->colors = $colors;
        } else {
            $this->colors = array($colors);
        }
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;

        $this->columnBuffer = array();
        for ($col = 0; $col < $columns; ++$col) {
            $current = array();

            $colMin = $col * 8;
            $colMax = $colMin + 7;

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
    }

    public function addString($string, $index = null)
    {
        if (null === $index) {
            $this->strings[] = $string;
        } else {
            $this->strings[$index] = $string;
        }

        return $this;
    }

    public function processFrame(FrameBuffer $buffer)
    {
        if (null === $this->currentString) {
            if (true === is_array($this->colors) && count($this->colors) > 0) {
                if (null !== $this->color) {
                    array_push($this->colors, $this->color);
                }

                $this->color = array_shift($this->colors);
            } else {
                $wheel = new Wheel;
            }

            // Prepare the string
            $this->currentString = array(
                'string' => array_shift($this->strings),
                'data' => array(),
                'progress' => array(),
                'color' => ($this->color === null) ? $wheel(mt_rand(0, 255)) : $this->color
            );

            if (null === $this->currentString['string']) {
                $buffer->stopLoop();
                return;
            }

            // Loop through letters of string
            for ($i = 0; $i < strlen($this->currentString['string']); ++$i) {
                // Get the letter
                $letter = substr($this->currentString['string'], $i, 1);

                if ($i == 0) {
                    $this->currentString['data'][] = static::$letters['PADDING'];
                }

                if (true === array_key_exists($letter, static::$letters)) {
                    $this->currentString['data'][] = static::$letters[$letter];
                } elseif (true === array_key_exists(strtoupper($letter), static::$letters)) {
                    $this->currentString['data'][] = static::$letters[strtoupper($letter)];
                } else {
                    $this->currentString['data'][] = static::$letters[' '];
                }

                $this->currentString['data'][] = static::$letters['PADDING'];
            }

            // Loop though the data array and build a column buffer
            $currentColumn = 0;
            $columns = array();

            foreach ($this->currentString['data'] as $index => $letter) {
                $cols = strlen($letter[0]);
                $rows = count($letter);

                for ($col = 0; $col < $cols; ++$col) {
                    for ($row = 0; $row < $rows; ++$row) {
                        $columns[$currentColumn][$row] = substr($letter[$row], $col, 1);
                    }

                    ++$currentColumn;
                }
            }

            $this->currentString['data'] = $columns;

            for ($i = 0; $i < $this->columns; ++$i) {
                $this->currentString['progress'][$i] = null;
            }

            $this->currentString['progress'][($this->columns - 1)] = array_shift($this->currentString['data']);
        }

        $next = count($this->currentString['data']) > 0 ? array_shift($this->currentString['data']) : null;

        // Shift Columns
        $current =& $this->currentString['progress'];
        for ($i = 0; $i < $this->columns; ++$i) {
            if ($i + 1 == $this->columns) {
                $current[$i] = $next;
            } else {
                $current[$i] = $current[($i + 1)];
            }
        }

        // Check to make sure all the columns are not null
        $allNull = true;
        foreach ($current as $col => $data) {
            if (true === $allNull) {
                $allNull = (null === $data);
            }
        }

        // Write the buffer to the screen
        foreach ($current as $col => $data) {
            if (null === $data) {
                $data = array(' ',' ',' ',' ',' ',' ',' ',' ');
            }

            foreach ($data as $row => $value) {
                $pixel = $buffer->getPixel($this->columnBuffer[$col][$row]);

                if ($value == ' ') {
                    $buffer->removePixel($this->columnBuffer[$col][$row]);
                } else {
                    $pixel->setColor($this->currentString['color']);
                    if (true === $this->antialias) {
                        switch ($value) {
                            case '-':
                                $pixel->setBrightness(0.25);
                                break;
                            case '+':
                                $pixel->setBrightness(0.5);
                                break;
                            case '*':
                                $pixel->setBrightness(0.75);
                                break;
                            case '#':
                                $pixel->setBrightness(1.0);
                                break;
                            default:
                                throw \Exception('Invalid font character: ' . $value);
                        }
                    } else {
                        $pixel->setBrightness(1.0);
                    }
                }
            }
        }

        // If All Null, set current string null
        if (true === $allNull) {
            if (true === $this->loop) {
                array_push($this->strings, $this->currentString['string']);
            }
            $this->currentString = null;
        }
    }

    public function enableAntialiasing()
    {
        $this->antialias = true;
        return $this;
    }

    public function disableAntialiasing()
    {
        $this->antialias = false;
        return $this;
    }
}

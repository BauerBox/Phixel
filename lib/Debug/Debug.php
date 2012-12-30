<?php

namespace BauerBox\Phixel\Debug;

class Debug
{
    protected static $enabled = true;

    public static function enable()
    {
        static::$enabled = true;
    }

    public static function log()
    {
        if (static::$enabled !== true) {
            return;
        }

        $args = func_num_args();

        for ($i = 0; $i < $args; ++$i) {
            echo sprintf(
                '[%s] %s%s',
                'Phixel',
                func_get_arg($i),
                PHP_EOL
            );
        }
    }

    public static function logBinary($data, $bits = 16)
    {
        if (static::$enabled !== true) {
            return;
        }

        echo sprintf(
            "[PhixelBin] %1$09d = %1$0{$bits}b (%2$02d bits)",
            (int) $data,
            $bits
        ) . PHP_EOL;
    }
}

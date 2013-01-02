<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\AbstractObject;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Debug\Debug;

class GeodesicSphere extends AbstractObject
{
    protected $zoneMap;

    public function __construct($zoneMap) {
        if (true === is_array($zoneMap)) {
            $this->loadZoneMap($zoneMap);
        } elseif (true === is_string($zoneMap)) {
            $file = \BauerBox\Phixel\Phixel::getFilePath($zoneMap, 'ini');
            
            if (false !== $file) {
                $this->loadZoneMap(parse_ini_file($file, true));
            } else {
                throw new \Exception('Invalid file shortcut: ' . $zoneMap);
            }
        } else {
            throw new \Exception('Zonemap parameter must be file path shortcut or array');
        }
    }

    public function processFrame(FrameBuffer $buffer)
    {
        ;
    }

    protected function loadZoneMap(array $zoneMap)
    {
        Debug::log('Loading Zones', print_r($zoneMap, true));
        exit(1);
    }
}

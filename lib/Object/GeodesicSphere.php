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

<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\AbstractObject;
use BauerBox\Phixel\Object\Pentagon;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Debug\Debug;
use BauerBox\Phixel\Color\Wheel;

class GeodesicSphere extends AbstractObject
{
    protected $objectsLoaded;
    protected $zoneMap;
    protected $zones;

    protected $cycleColors;
    protected $wheel;
    protected $position;

    public function __construct($zoneMap, $cycleColors = null)
    {
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

        $this->cycleColors = array();

        if (null !== $cycleColors) {
            $this->cycleColors = $cycleColors;
        }

        $this->wheel = new Wheel;
    }

    public function processFrame(FrameBuffer $buffer)
    {
        if ($this->objectsLoaded === true) {
            $center = $this->wheel($this->position);
            $inner = $this->wheel($this->wheel->next($this->position));
            $outer = $this->wheel($this->wheel->next($this->wheel->next($this->position)));
            $this->position = $this->wheel->next($this->position);

            /*
            $center = array_shift($this->cycleColors);
            $inner = array_shift($this->cycleColors);
            $outer = array_shift($this->cycleColors);
            */

            foreach ($this->zones as $zone) {
                $zone->drawOuterRing($outer, 0.5);
                $zone->drawInnerRing($inner, 0.5);
                $zone->drawCenter($center, 1.0);
            }
            
            /*
            array_push($this->cycleColors, $outer);
            array_push($this->cycleColors, $center);
            array_push($this->cycleColors, $inner);
            */
        } else {
            Debug::log('Loading Zones');
            foreach ($this->zoneMap as $index => $zone) {
                Debug::log(' - Loading Zone: ' . $index);
                $this->zones[$index] = new Pentagon($zone, ($index < 6) ? Pentagon::ORIENTATION_POINT_NORTH : Pentagon::ORIENTATION_POINT_SOUTH);
                $buffer->attachObject($this->zones[$index]);
            }

            $this->objectsLoaded = true;
        }
    }

    protected function loadZoneMap(array $zoneMap)
    {
        Debug::log('Loading Zones', print_r($zoneMap, true));

        foreach ($zoneMap as $zone => $data) {
            if (preg_match('@^Zone(?P<index>\d+)$@', $zone, $match)) {
                Debug::log('Found zone: ' . $match['index']);

                if (true === array_key_exists('led', $data) && true === (count($data['led']) == 16)) {
                    $this->zoneMap[(int) $match['index']] = $data['led'];
                } else {
                    throw new \Exception('Invalid LED count for zone: ' . $match['index']);
                }
            } else {
                throw new \Exception('Invalid zone array');
            }
        }

        if (count($this->zoneMap) != 12) {
            throw new \Exception('Invalid zone count');
        }

        Debug::log('Zone file loaded');
    }
}

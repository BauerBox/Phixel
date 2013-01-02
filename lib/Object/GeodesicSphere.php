<?php

namespace BauerBox\Phixel\Object;

use BauerBox\Phixel\Object\AbstractObject;
use BauerBox\Phixel\Object\Pentagon;
use BauerBox\Phixel\Buffer\FrameBuffer;
use BauerBox\Phixel\Debug\Debug;

class GeodesicSphere extends AbstractObject
{
    protected $objectsLoaded;
    protected $zoneMap;
    protected $zones;

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
        if ($this->objectsLoaded === true) {
            foreach ($this->zones as $zone) {
                $zone->fill(mt_rand(0x000000, 0xffffff), 0.5);
            }
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

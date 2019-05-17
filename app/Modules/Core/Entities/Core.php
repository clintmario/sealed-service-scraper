<?php
namespace App\Modules\Core\Entities;

class Core
{
    private static $services = [];

    public static function getService($serviceClassName)
    {
        if (empty(self::$services[$serviceClassName])) {
            self::$services[$serviceClassName] = new $serviceClassName(app());
        }

        return self::$services[$serviceClassName];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 13/06/2018
 * Time: 20:25
 */

namespace ZONNY\Utils;

use Doctrine\ORM\Configuration;

class PostGIS
{

    /**
     * Add support for PostGIS function
     * @param $configuration
     */
    public static function addSupportFunctions(&$configuration){
        self::addSTDistance($configuration);
        self:self::addSTPOINT($configuration);
    }

    /**
     * Add support of PostGIS ST_DISTANCE() function
     * @param Configuration $configuration
     */
    private static function addSTDistance(Configuration &$configuration){
        $configuration->addCustomStringFunction(
            'ST_Distance',
            'Jsor\Doctrine\PostGIS\Functions\ST_Distance'
        );
    }

    /**
     * Add support of PostGIS ST_POINT() function
     * @param Configuration $configuration
     */
    private static function addSTPOINT(Configuration &$configuration){
        $configuration->addCustomStringFunction(
            'ST_Point',
            'Jsor\Doctrine\PostGIS\Functions\ST_Point'
        );
    }

}
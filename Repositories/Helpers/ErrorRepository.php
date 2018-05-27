<?php
namespace ZONNY\Repositories\Helpers;

use ZONNY\Utils\Database;
use \ZONNY\Models\Helpers\Error;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:07
 */

class ErrorRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(Error::class);
    }

}
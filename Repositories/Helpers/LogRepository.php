<?php
namespace ZONNY\Repositories\Helpers;

use Doctrine\ORM\EntityRepository;
use ZONNY\Models\Helpers\Log;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:08
 */

class LogRepository extends EntityRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(Log::class);
    }


}
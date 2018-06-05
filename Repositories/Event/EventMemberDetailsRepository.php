<?php
namespace ZONNY\Repositories\Event;

use Doctrine\ORM\EntityRepository;
use ZONNY\Models\Event\EventMemberDetails;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:06
 */

class EventMemberDetailsRepository extends EntityRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(EventMemberDetails::class);
    }

}
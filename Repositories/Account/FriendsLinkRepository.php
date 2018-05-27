<?php
namespace ZONNY\Repositories\Account;

use ZONNY\Models\Account\FriendsLink;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 20:55
 */

class FriendsLinkRepository
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(FriendsLink::class);
    }
}
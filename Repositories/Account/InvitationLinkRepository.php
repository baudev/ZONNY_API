<?php
namespace ZONNY\Repositories\Account;

use Doctrine\ORM\EntityRepository;
use ZONNY\Models\Account\InvitationLink;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 20:57
 */

class InvitationLinkRepository extends EntityRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(InvitationLink::class);
    }

}
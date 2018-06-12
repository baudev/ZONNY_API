<?php
namespace ZONNY\Repositories\Account;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ZONNY\Models\Account\User;
use ZONNY\Repositories\Event\EventMemberDetailsRepository;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 20:53
 */

class UserRepository extends EntityRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(User::class);
    }

    /**
     * Return the QueryBuilder of the current repository
     * @return QueryBuilder
     */
    public function getQueryBuilder(){
        return $this->createQueryBuilder('u');
    }

}
<?php
namespace ZONNY\Repositories\Account;

use Doctrine\ORM\EntityRepository;
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

    /***
     * Return the query builder which returns the level of the user
     * @param User $user
     * @return \Doctrine\ORM\Query
     * @throws \Doctrine\ORM\ORMException
     */
    public function getLevelQueryBuilder(User $user){
        // We use the repository of EventMemberDetails
        $qb = EventMemberDetailsRepository::getRepository()->createQueryBuilder('e');
        $qb->select('e')
            ->where('e.invitedFriend = :userId')
            ->setParameter(':userId', $user->getId())
            ->andWhere('e.creationDatetime >= :creationDatetime')
            ->setParameter(':creationDatetime', (new \DateTime())->modify('-7 days'));
        return $qb->getQuery();
    }

}
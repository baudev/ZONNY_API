<?php
namespace ZONNY\Repositories\Account;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ZONNY\Models\Account\FriendsLink;
use ZONNY\Models\Account\User;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 20:55
 */

class FriendsLinkRepository extends EntityRepository
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(FriendsLink::class);
    }

    /**
     * Return the QueryBuilder of the current repository
     * @return QueryBuilder
     */
    public function getQueryBuilder(){
        return $this->createQueryBuilder('f');
    }

    /**
     * Return all user's friends
     * @param User $user
     * @return mixed
     */
    public function getUserFriends(User $user){
        $qb = $this->getQueryBuilder();

        $this->joinUsers1AndFriendLinks($qb);
        $this->whereUser1IsUser($qb, $user);
        // TODO order by friends or other criteria
        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Join the table User and FriendLink
     * @param QueryBuilder $qb
     */
    public function joinUsers1AndFriendLinks(QueryBuilder $qb){
        $qb
            ->addSelect('user1')
            ->join('f.user1', 'user1');
    }

    /**
     * Where join User1 is the user
     * @param QueryBuilder $qb
     * @param User $user
     */
    public function whereUser1IsUser(QueryBuilder $qb, User $user){
        $qb
            ->orWhere('user1.id = :id')
            ->setParameter('id', $user->getId());
    }

    // TODO implements into FriendClass
    public function addDistanceBetweenUserAndFriend(QueryBuilder $qb, User $user){
        $qb
            ->addSelect('ST_Distance(Geography(ST_Point(:user_longitude,:user_latitude)), Geography(ST_Point(user1.longitude, user1.longitude)))/1000 as distance')
            ->setParameter('user_longitude', $user->getLongitude())
            ->setParameter('user_latitude', $user->getLatitude());
    }
}
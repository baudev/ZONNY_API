<?php
namespace ZONNY\Repositories\Event;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ZONNY\Models\Account\User;
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

    /**
     * Return the QueryBuilder of the current repository
     * @return QueryBuilder
     */
    public function getQueryBuilder(){
        return $this->createQueryBuilder('e');
    }

    /**
     * Return all details concerning user's invitations that are newer than one week
     * @param User $user
     * @return mixed
     */
    public function getUserInvitationsNewerThanOneWeek(User $user){
        $qb = $this->getQueryBuilder();

        $this->getUserInvitations($qb, $user);
        $this->whereNewerThanOneWeek($qb);

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Return the details of the user invitations
     * @param QueryBuilder $qb
     * @param User $user
     */
    public function getUserInvitations(QueryBuilder $qb, User $user){
        $qb
            ->where('e.invitedFriend = :userId')
            ->setParameter(':userId', $user->getId());
    }

    /**
     * Return details of the invitation in the last week
     * @param QueryBuilder $qb
     */
    public function whereNewerThanOneWeek(QueryBuilder $qb){
        $qb
            ->andWhere('e.creationDatetime >= :creationDatetime')
            ->setParameter(':creationDatetime', (new \DateTime())->modify('-7 days'));
    }

}
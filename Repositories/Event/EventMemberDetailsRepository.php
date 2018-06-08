<?php
namespace ZONNY\Repositories\Event;

use DateTime;
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
     * @param User $user
     * @return mixed
     */
    public function getCurrentEventsWhereUserIsComing(User $user){
        $qb = $this->getQueryBuilder();

        $this->joinEventMemberDetailsAndEvents($qb);
        $this->whereResponseIs($qb, EventMemberDetails::IS_COMING);
        $this->joinWhereEventsHaveStarted($qb);
        $this->joinWhereEventsHaveNotEnded($qb);
        $this->getUserInvitations($qb, $user);

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Join the two objects EventMemberDetails and Events
     * @param QueryBuilder $qb
     * @return mixed
     */
    public function joinEventMemberDetailsAndEvents(QueryBuilder $qb){
        $qb
            ->addSelect('e, events')
            ->join('e.event', 'events');
    }

    /**
     * Get events which have already started
     * @param QueryBuilder $qb
     */
    public function joinWhereEventsHaveStarted(QueryBuilder $qb){
        $qb
            ->andWhere('events.startDatetime <= :start_datetime')
            ->setParameter(':start_datetime', new DateTime());
    }

    /**
     * Get events which have not ended
     * @param QueryBuilder $qb
     */
    public function joinWhereEventsHaveNotEnded(QueryBuilder $qb){
        $qb
            ->andWhere('events.endDatetime >= :end_datetime')
            ->setParameter(':end_datetime', new DateTime());
    }

    /**
     * Get the details of the invitations with the corresponding response
     * @param QueryBuilder $qb
     * @param int $response
     */
    public function whereResponseIs(QueryBuilder $qb, int $response){
        $qb
            ->andWhere('e.response = :response')
            ->setParameter(':response', $response);
    }

    /**
     * Return the details of the user invitations
     * @param QueryBuilder $qb
     * @param User $user
     */
    public function getUserInvitations(QueryBuilder $qb, User $user){
        $qb
            ->andwhere('e.invitedFriend = :userId')
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
<?php
namespace ZONNY\Repositories\Event;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use ZONNY\Models\Event\Event;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:05
 */

class EventRepository extends EntityRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(Event::class);
    }

    /**
     * Return the QueryBuilder of the current repository
     * @return QueryBuilder
     */
    public function getQueryBuilder(){
        return $this->createQueryBuilder('e');
    }

    /**
     * Get events that have already started
     * @param QueryBuilder $qb
     */
    public function getEventsWhichHaveStarted(QueryBuilder $qb){
        $qb
            ->andWhere('e.startDatetime <= :start_datetime')
            ->setParameter(':start_datetime', new DateTime());
    }

    /**
     * Get events that has not ended
     * @param QueryBuilder $qb
     */
    public function getEventsWhichHaveNotEnded(QueryBuilder $qb){
        $qb
            ->andWhere('e.endDatetime >= :end_datetime')
            ->setParameter(':end_datetime', new DateTime());
    }

}
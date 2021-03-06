<?php
namespace ZONNY\Repositories\Chat;

use Doctrine\ORM\EntityRepository;
use ZONNY\Models\Chat\ChatParticipant;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:02
 */

class ChatParticipantRepository extends EntityRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(ChatParticipant::class);
    }

}
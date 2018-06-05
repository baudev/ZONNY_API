<?php
namespace ZONNY\Repositories\Chat;

use Doctrine\ORM\EntityRepository;
use ZONNY\Models\Chat\State;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:02
 */

class StateRepository extends EntityRepository
{


    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(State::class);
    }

}
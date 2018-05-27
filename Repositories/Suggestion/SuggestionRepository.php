<?php
namespace ZONNY\Repositories\Suggestion;

use ZONNY\Models\Suggestion\Suggestion;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:10
 */

class SuggestionRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(Suggestion::class);
    }

}
<?php
namespace ZONNY\Repositories\Suggestion;

use ZONNY\Models\Suggestion\SuggestionCategory;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:11
 */

class SuggestionCategoryRepository
{

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(SuggestionCategory::class);
    }

}
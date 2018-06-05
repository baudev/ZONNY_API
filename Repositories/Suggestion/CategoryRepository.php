<?php
namespace ZONNY\Repositories\Suggestion;

use Doctrine\ORM\EntityRepository;
use ZONNY\Models\Suggestion\Category;
use ZONNY\Utils\Database;

/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 21:09
 */

class CategoryRepository extends EntityRepository
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getRepository(){
        return Database::getEntityManager()->getRepository(Category::class);
    }


}
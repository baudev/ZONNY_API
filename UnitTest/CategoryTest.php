<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 14:59
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Suggestion\Category;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Suggestion\CategoryRepository;
use ZONNY\Utils\Database;

class CategoryTest extends TestCase
{
    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $categoryRepository = CategoryRepository::getRepository();

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);
        $entityManager->flush();
        /**
         * @var Category $category_copy
         */
        $category_copy = $categoryRepository->find($category->getId());
        $this->assertEquals($category->getId(), $category_copy->getId());

        $entityManager->remove($category);
        $entityManager->flush();
    }

    public function testInsertion(){
        $entityManager = Database::getEntityManager();

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);
        $entityManager->flush();

        $this->assertNotNull($category->getId());

        $entityManager->remove($category);
        $entityManager->flush();
    }

    public function testDeletion(){
        $entityManager = Database::getEntityManager();

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);
        $entityManager->flush();

        $entityManager->remove($category);
        $entityManager->flush();
        $this->assertNull($category->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $categoryRepository = CategoryRepository::getRepository();

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);
        $entityManager->flush();
        $category->setName("Sport");
        $entityManager->flush();
        /**
         * @var Category $category_copy
         */
        $category_copy = $categoryRepository->find($category->getId());
        $this->assertEquals("Sport", $category_copy->getName());

        $entityManager->remove($category);
        $entityManager->flush();
    }
}

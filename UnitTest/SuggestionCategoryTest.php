<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 15:04
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Suggestion\Category;
use ZONNY\Models\Suggestion\Suggestion;
use ZONNY\Models\Suggestion\SuggestionCategory;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Suggestion\SuggestionCategoryRepository;
use ZONNY\Utils\Database;

class SuggestionCategoryTest extends TestCase
{
    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $suggestionCategoryRepository = SuggestionCategoryRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);

        $suggestionCategory = new SuggestionCategory();
        $suggestionCategory->setSuggestion($suggestion);
        $suggestionCategory->setCategory($category);
        $suggestionCategory->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestionCategory);
        $entityManager->flush();
        /**
         * @var SuggestionCategory $suggestionCategory_copy
         */
        $suggestionCategory_copy = $suggestionCategoryRepository->find($suggestionCategory->getId());
        $this->assertEquals($suggestionCategory->getId(), $suggestionCategory_copy->getId());

        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function testInsertion(){
        $entityManager = Database::getEntityManager();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);

        $suggestionCategory = new SuggestionCategory();
        $suggestionCategory->setSuggestion($suggestion);
        $suggestionCategory->setCategory($category);
        $suggestionCategory->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestionCategory);
        $entityManager->flush();

        $this->assertNotNull($suggestionCategory->getId());

        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function testDeletion(){
        $entityManager = Database::getEntityManager();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);

        $suggestionCategory = new SuggestionCategory();
        $suggestionCategory->setSuggestion($suggestion);
        $suggestionCategory->setCategory($category);
        $suggestionCategory->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestionCategory);
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($suggestionCategory->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $suggestionCategoryRepository = SuggestionCategoryRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $category = new Category();
        $category->setName("Night");
        $category->setCreationDatetime(new DateTime());
        $entityManager->persist($category);

        $suggestionCategory = new SuggestionCategory();
        $suggestionCategory->setSuggestion($suggestion);
        $suggestionCategory->setCategory($category);
        $suggestionCategory->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestionCategory);
        $entityManager->flush();
        $suggestion->setIsRecurrent(true);
        $entityManager->flush();
        /**
         * @var SuggestionCategory $suggestionCategory_copy
         */
        $suggestionCategory_copy = $suggestionCategoryRepository->find($suggestionCategory->getId());
        $this->assertTrue($suggestionCategory_copy->getSuggestion()->getisRecurrent());

        $entityManager->remove($user);
        $entityManager->flush();
    }
}

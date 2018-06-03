<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 14:41
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Suggestion\Suggestion;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Suggestion\SuggestionRepository;
use ZONNY\Utils\Database;

class SuggestionTest extends TestCase
{


    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $suggestionRepository = SuggestionRepository::getRepository();

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
        $entityManager->flush();
        /**
         * @var Suggestion $suggestion_copy
         */
        $suggestion_copy = $suggestionRepository->find($suggestion->getId());
        $this->assertEquals($suggestion->getId(), $suggestion_copy->getId());

        $entityManager->remove($suggestion);
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
        $entityManager->flush();

        $this->assertNotNull($suggestion->getId());

        $entityManager->remove($suggestion);
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
        $entityManager->flush();

        $entityManager->remove($suggestion);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($suggestion->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $suggestionRepository = SuggestionRepository::getRepository();

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
        $entityManager->flush();
        $suggestion->setIsRecurrent(true);
        $entityManager->flush();
        /**
         * @var Suggestion $suggestion_copy
         */
        $suggestion_copy = $suggestionRepository->find($suggestion->getId());
        $this->assertTrue($suggestion_copy->getisRecurrent());

        $entityManager->remove($suggestion);
        $entityManager->remove($user);
        $entityManager->flush();
    }


}

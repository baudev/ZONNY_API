<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 14:51
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Suggestion\DayTime;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Suggestion\Suggestion;
use ZONNY\Repositories\Suggestion\DayTimeRepository;
use ZONNY\Utils\Database;

class DayTimeTest extends TestCase
{
    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $dayTimeRepository = DayTimeRepository::getRepository();

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

        $dayTime = new DayTime();
        $dayTime->setSuggestion($suggestion);
        $dayTime->setDay(0);
        $dayTime->setDayOpen(new DateTime());
        $dayTime->setDayClose(new DateTime());
        $dayTime->setCreationDatetime(new DateTime());
        $entityManager->persist($dayTime);
        $entityManager->flush();

        /**
         * @var DayTime $dayTime_copy
         */
        $dayTime_copy = $dayTimeRepository->find($dayTime->getId());
        $this->assertEquals($dayTime->getId(), $dayTime_copy->getId());
        
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

        $dayTime = new DayTime();
        $dayTime->setSuggestion($suggestion);
        $dayTime->setDay(0);
        $dayTime->setDayOpen(new DateTime());
        $dayTime->setDayClose(new DateTime());
        $dayTime->setCreationDatetime(new DateTime());
        $entityManager->persist($dayTime);
        $entityManager->flush();

        $this->assertNotNull($dayTime->getId());

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

        $dayTime = new DayTime();
        $dayTime->setSuggestion($suggestion);
        $dayTime->setDay(0);
        $dayTime->setDayOpen(new DateTime());
        $dayTime->setDayClose(new DateTime());
        $dayTime->setCreationDatetime(new DateTime());
        $entityManager->persist($dayTime);
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($dayTime->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $dayTimeRepository = DayTimeRepository::getRepository();

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

        $dayTime = new DayTime();
        $dayTime->setSuggestion($suggestion);
        $dayTime->setDay(0);
        $dayTime->setDayOpen(new DateTime());
        $dayTime->setDayClose(new DateTime());
        $dayTime->setCreationDatetime(new DateTime());
        $entityManager->persist($dayTime);
        $entityManager->flush();
        $dayTime->setDay(1);
        $entityManager->flush();
        /**
         * @var DayTime $dayTime_copy
         */
        $dayTime_copy = $dayTimeRepository->find($dayTime->getId());
        $this->assertEquals(1, $dayTime_copy->getDay());

        $entityManager->remove($user);
        $entityManager->flush();
    }
}

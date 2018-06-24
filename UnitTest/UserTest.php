<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 18:16
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\FriendsLink;
use ZONNY\Models\Account\User;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Account\FriendsLinkRepository;
use ZONNY\Repositories\Account\UserRepository;
use ZONNY\Utils\Database;


class UserTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $userRepository = UserRepository::getRepository();
        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);
        $entityManager->flush();
        /**
         * @var User $user_copy
         */
        $user_copy = $userRepository->find($user->getId());
        $this->assertEquals($user->getId(), $user_copy->getId());

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
        $entityManager->flush();
        $this->assertNotNull($user->getId());

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
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($user->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);
        $entityManager->flush();
        $user->setName("user2");
        $entityManager->flush();
        /**
         * @var User $user_copy
         */
        $user_copy = UserRepository::getRepository()->find($user->getId());
        $this->assertEquals("user2", $user_copy->getName());

        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function testGetLevel(){
        $entityManager = Database::getEntityManager();
        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);
        $entityManager->flush();

        // as the user has any passed events, his level should be the default one
        $this->assertEquals(10, $user->getLevel());

        $entityManager->remove($user);
        $entityManager->flush();
    }

}

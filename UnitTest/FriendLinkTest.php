<?php

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use Doctrine\Common\Util\Debug;
use ZONNY\Models\Account\FriendLink;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Account\FriendsLink;
use ZONNY\Models\Account\User;
use ZONNY\Repositories\Account\FriendsLinkRepository;
use ZONNY\Utils\Database;

class FriendLinkTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $friendslinkRepository = FriendsLinkRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $friendslink = new FriendsLink();
        $friendslink->setUser1($user);
        $friendslink->setUser2($user2);
        $friendslink->setAuthorization(true);
        $friendslink->setCreationDatetime(new DateTime());

        $entityManager->persist($friendslink);
        $entityManager->flush();
        /**
         * @var User $user_copy
         */
        $friendslink_copy = $friendslinkRepository->find($friendslink->getId());
        $this->assertEquals($friendslink->getId(), $friendslink_copy->getId());

        $entityManager->remove($friendslink);
        $entityManager->remove($user);
        $entityManager->remove($user2);
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

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $friendslink = new FriendsLink();
        $friendslink->setUser1($user);
        $friendslink->setUser2($user2);
        $friendslink->setAuthorization(true);
        $friendslink->setCreationDatetime(new DateTime());

        $entityManager->persist($friendslink);
        $entityManager->flush();

        $this->assertNotNull($friendslink->getId());

        $entityManager->remove($friendslink);
        $entityManager->remove($user);
        $entityManager->remove($user2);
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

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $friendslink = new FriendsLink();
        $friendslink->setUser1($user);
        $friendslink->setUser2($user2);
        $friendslink->setAuthorization(true);
        $friendslink->setCreationDatetime(new DateTime());

        $entityManager->persist($friendslink);
        $entityManager->flush();

        $entityManager->remove($friendslink);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
        $this->assertNull($friendslink->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $friendslinkRepository = FriendsLinkRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $friendslink = new FriendsLink();
        $friendslink->setUser1($user);
        $friendslink->setUser2($user2);
        $friendslink->setAuthorization(true);
        $friendslink->setCreationDatetime(new DateTime());

        $entityManager->persist($friendslink);
        $entityManager->flush();
        $friendslink->setAuthorization(false);
        $entityManager->flush();
        /**
         * @var FriendsLink $friendslink_copy
         */
        $friendslink_copy = $friendslinkRepository->find($friendslink->getId());
        $this->assertFalse($friendslink_copy->getAuthorization());

        $entityManager->remove($friendslink);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
    }

    public function testGetUserFriends(){

        $entityManager = Database::getEntityManager();
        $friendslinkRepository = FriendsLinkRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setLongitude(10.0);
        $user->setLatitude(10.0);
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $user2 = new User();
        $user2->setName("user2");
        $user2->setKeyApp("key2");
        $user->setLongitude(20.0);
        $user->setLatitude(20.0);
        $user2->setPlatform(1);
        $user2->setCreationDatetime(new DateTime());
        $entityManager->persist($user2);

        $friendslink = new FriendsLink();
        $friendslink->setUser1($user);
        $friendslink->setUser2($user2);
        $friendslink->setAuthorization(true);
        $friendslink->setCreationDatetime(new DateTime());

        $entityManager->persist($friendslink);
        $entityManager->flush();

        // we check that the friend of the user is well retrieved in the getUserFriends function
        $this->assertEquals($user2->getId(), $friendslinkRepository->getUserFriends($user)[0]->getUser2()->getId());

        $entityManager->remove($friendslink);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();

    }

}

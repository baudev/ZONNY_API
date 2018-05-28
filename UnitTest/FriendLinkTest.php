<?php

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
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
        $friendslink->setAuthorizationUser1(true);
        $friendslink->setAuthorizationUser2(true);
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
        $friendslink->setAuthorizationUser1(true);
        $friendslink->setAuthorizationUser2(true);
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
        $friendslink->setAuthorizationUser1(true);
        $friendslink->setAuthorizationUser2(true);
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
        $friendslink->setAuthorizationUser1(true);
        $friendslink->setAuthorizationUser2(true);
        $friendslink->setCreationDatetime(new DateTime());

        $entityManager->persist($friendslink);
        $entityManager->flush();
        $friendslink->setAuthorizationUser2(false);
        $entityManager->flush();
        /**
         * @var FriendsLink $friendslink_copy
         */
        $friendslink_copy = FriendsLinkRepository::getRepository()->find($friendslink->getId());
        $this->assertFalse($friendslink_copy->getAuthorizationUser2());

        $entityManager->remove($friendslink);
        $entityManager->remove($user);
        $entityManager->remove($user2);
        $entityManager->flush();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 28/05/2018
 * Time: 23:13
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\InvitationLink;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Account\User;
use ZONNY\Repositories\Account\InvitationLinkRepository;
use ZONNY\Utils\Database;

class InvitationLinkTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = InvitationLinkRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);



        $invitationLink = new InvitationLink();
        $invitationLink->setUser($user);
        $invitationLink->setTokenId("token_example");
        $invitationLink->setUsed(true);
        $invitationLink->setCreationDatetime(new DateTime());
        $entityManager->persist($invitationLink);
        $entityManager->flush();
        /**
         * @var InvitationLink $invitationLink_copy
         */
        $invitationLink_copy = $invitationLinkRepository->find($invitationLink->getId());
        $this->assertEquals($invitationLink->getId(), $invitationLink_copy->getId());

        $entityManager->remove($invitationLink);
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



        $invitationLink = new InvitationLink();
        $invitationLink->setUser($user);
        $invitationLink->setTokenId("token_example");
        $invitationLink->setUsed(true);
        $invitationLink->setCreationDatetime(new DateTime());
        $entityManager->persist($invitationLink);
        $entityManager->flush();

        $this->assertNotNull($invitationLink->getId());

        $entityManager->remove($invitationLink);
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



        $invitationLink = new InvitationLink();
        $invitationLink->setUser($user);
        $invitationLink->setTokenId("token_example");
        $invitationLink->setUsed(true);
        $invitationLink->setCreationDatetime(new DateTime());
        $entityManager->persist($invitationLink);
        $entityManager->flush();

        $entityManager->remove($invitationLink);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($invitationLink->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = InvitationLinkRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);



        $invitationLink = new InvitationLink();
        $invitationLink->setUser($user);
        $invitationLink->setTokenId("token_example");
        $invitationLink->setUsed(true);
        $invitationLink->setCreationDatetime(new DateTime());
        $entityManager->persist($invitationLink);
        $entityManager->flush();
        $invitationLink->setUsed(false);
        $entityManager->flush();
        /**
         * @var InvitationLink $invitationLink_copy
         */
        $invitationLink_copy = $invitationLinkRepository->find($invitationLink->getId());
        $this->assertFalse($invitationLink->getUsed());

        $entityManager->remove($invitationLink);
        $entityManager->remove($user);
        $entityManager->flush();
    }

}

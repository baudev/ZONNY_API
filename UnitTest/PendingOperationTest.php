<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 29/05/2018
 * Time: 22:52
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Chat\PendingOperation;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Event\Event;
use ZONNY\Repositories\Chat\PendingOperationRepository;
use ZONNY\Utils\Database;

class PendingOperationTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = PendingOperationRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $pendingOperation = new PendingOperation();
        $pendingOperation->setUser($user);
        $pendingOperation->setOperationIdForUser(4054);
        $pendingOperation->setJsonContent('{}');
        $pendingOperation->setCreationDatetime(new DateTime());
        $entityManager->persist($pendingOperation);
        $entityManager->flush();
        /**
         * @var PendingOperation $pendingOperation_copy
         */
        $pendingOperation_copy = $invitationLinkRepository->find($pendingOperation->getId());
        $this->assertEquals($pendingOperation->getId(), $pendingOperation_copy->getId());

        $entityManager->remove($pendingOperation);
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

        $pendingOperation = new PendingOperation();
        $pendingOperation->setUser($user);
        $pendingOperation->setOperationIdForUser(4054);
        $pendingOperation->setJsonContent('{}');
        $pendingOperation->setCreationDatetime(new DateTime());
        $entityManager->persist($pendingOperation);
        $entityManager->flush();

        $this->assertNotNull($pendingOperation->getId());

        $entityManager->remove($pendingOperation);
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

        $pendingOperation = new PendingOperation();
        $pendingOperation->setUser($user);
        $pendingOperation->setOperationIdForUser(4054);
        $pendingOperation->setJsonContent('{}');
        $pendingOperation->setCreationDatetime(new DateTime());
        $entityManager->persist($pendingOperation);
        $entityManager->flush();

        $entityManager->remove($pendingOperation);
        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($pendingOperation->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = PendingOperationRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $pendingOperation = new PendingOperation();
        $pendingOperation->setUser($user);
        $pendingOperation->setOperationIdForUser(4054);
        $pendingOperation->setJsonContent('{}');
        $pendingOperation->setCreationDatetime(new DateTime());
        $entityManager->persist($pendingOperation);
        $entityManager->flush();
        $pendingOperation->setOperationIdForUser(4055);
        $entityManager->flush();
        /**
         * @var PendingOperation $pendingOperation_copy
         */
        $pendingOperation_copy = $invitationLinkRepository->find($pendingOperation->getId());
        $this->assertEquals(4055, $pendingOperation_copy->getOperationIdForUser());

        $entityManager->remove($pendingOperation);
        $entityManager->remove($user);
        $entityManager->flush();
    }

}

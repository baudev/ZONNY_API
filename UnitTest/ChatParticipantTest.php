<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 29/05/2018
 * Time: 22:48
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Chat\ChatParticipant;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Event\Event;
use ZONNY\Repositories\Chat\ChatParticipantRepository;
use ZONNY\Utils\Database;

class ChatParticipantTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = ChatParticipantRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $event = new Event();
        $event->setCreator($user);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new DateTime());
        $entityManager->persist($event);

        $chatParticipant = new ChatParticipant();
        $chatParticipant->setUser($user);
        $chatParticipant->setEvent($event);
        $chatParticipant->setCreationDatetime(new DateTime());
        $entityManager->persist($chatParticipant);
        $entityManager->flush();
        /**
         * @var ChatParticipant $chatParticipant_copy
         */
        $chatParticipant_copy = $invitationLinkRepository->find($chatParticipant->getId());
        $this->assertEquals($chatParticipant->getId(), $chatParticipant_copy->getId());

        $entityManager->remove($chatParticipant);
        $entityManager->remove($user);
        $entityManager->remove($event);
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

        $event = new Event();
        $event->setCreator($user);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new DateTime());
        $entityManager->persist($event);

        $chatParticipant = new ChatParticipant();
        $chatParticipant->setUser($user);
        $chatParticipant->setEvent($event);
        $chatParticipant->setCreationDatetime(new DateTime());
        $entityManager->persist($chatParticipant);
        $entityManager->flush();

        $this->assertNotNull($chatParticipant->getId());

        $entityManager->remove($chatParticipant);
        $entityManager->remove($user);
        $entityManager->remove($event);
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

        $event = new Event();
        $event->setCreator($user);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new DateTime());
        $entityManager->persist($event);

        $chatParticipant = new ChatParticipant();
        $chatParticipant->setUser($user);
        $chatParticipant->setEvent($event);
        $chatParticipant->setCreationDatetime(new DateTime());
        $entityManager->persist($chatParticipant);
        $entityManager->flush();

        $entityManager->remove($chatParticipant);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->flush();
        $this->assertNull($chatParticipant->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = ChatParticipantRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $event = new Event();
        $event->setCreator($user);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new DateTime());
        $entityManager->persist($event);

        $chatParticipant = new ChatParticipant();
        $chatParticipant->setUser($user);
        $chatParticipant->setEvent($event);
        $chatParticipant->setCreationDatetime(new DateTime());
        $entityManager->persist($chatParticipant);
        $entityManager->flush();
        $user->setKeyApp("key3");
        $chatParticipant->setUser($user);
        $entityManager->flush();
        /**
         * @var ChatParticipant $chatParticipant_copy
         */
        $chatParticipant_copy = $invitationLinkRepository->find($chatParticipant->getId());
        $this->assertEquals('key3', $chatParticipant_copy->getUser()->getKeyApp());

        $entityManager->remove($chatParticipant);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->flush();
    }

}

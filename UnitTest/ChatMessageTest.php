<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 29/05/2018
 * Time: 22:29
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Chat\ChatMessage;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Event\Event;
use ZONNY\Repositories\Chat\ChatMessageRepository;
use ZONNY\Utils\Database;

class ChatMessageTest extends TestCase
{


    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = ChatMessageRepository::getRepository();

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

        $chatMessage = new ChatMessage();
        $chatMessage->setUser($user);
        $chatMessage->setEvent($event);
        $chatMessage->setType("file");
        $chatMessage->setContent("/link/to/file");
        $chatMessage->setCreationDatetime(new DateTime());
        $entityManager->persist($chatMessage);
        $entityManager->flush();
        /**
         * @var ChatMessage $chatMessage_copy
         */
        $chatMessage_copy = $invitationLinkRepository->find($chatMessage->getId());
        $this->assertEquals($chatMessage->getId(), $chatMessage_copy->getId());

        $entityManager->remove($chatMessage);
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

        $chatMessage = new ChatMessage();
        $chatMessage->setUser($user);
        $chatMessage->setEvent($event);
        $chatMessage->setType("file");
        $chatMessage->setContent("/link/to/file");
        $chatMessage->setCreationDatetime(new DateTime());
        $entityManager->persist($chatMessage);
        $entityManager->flush();

        $this->assertNotNull($chatMessage->getId());

        $entityManager->remove($chatMessage);
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

        $chatMessage = new ChatMessage();
        $chatMessage->setUser($user);
        $chatMessage->setEvent($event);
        $chatMessage->setType("file");
        $chatMessage->setContent("/link/to/file");
        $chatMessage->setCreationDatetime(new DateTime());
        $entityManager->persist($chatMessage);
        $entityManager->flush();

        $entityManager->remove($chatMessage);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->flush();
        $this->assertNull($chatMessage->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = ChatMessageRepository::getRepository();

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

        $chatMessage = new ChatMessage();
        $chatMessage->setUser($user);
        $chatMessage->setEvent($event);
        $chatMessage->setType("file");
        $chatMessage->setContent("/link/to/file");
        $chatMessage->setCreationDatetime(new DateTime());
        $entityManager->persist($chatMessage);
        $entityManager->flush();
        $chatMessage->setContent('/link/to/file2');
        $entityManager->flush();
        /**
         * @var ChatMessage $chatMessage_copy
         */
        $chatMessage_copy = $invitationLinkRepository->find($chatMessage->getId());
        $this->assertEquals('/link/to/file2', $chatMessage_copy->getContent());

        $entityManager->remove($chatMessage);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->flush();
    }

}

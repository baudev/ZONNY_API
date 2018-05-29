<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 29/05/2018
 * Time: 23:03
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Chat\ChatMessage;
use ZONNY\Models\Chat\State;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Event\Event;
use ZONNY\Repositories\Chat\StateRepository;
use ZONNY\Utils\Database;

class StateTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = StateRepository::getRepository();

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

        $state = new State();
        $state->setMessage($chatMessage);
        $state->setState(2);
        $state->setCreationDatetime(new DateTime());
        $entityManager->persist($state);
        $entityManager->flush();
        /**
         * @var State $state_copy
         */
        $state_copy = $invitationLinkRepository->find($state->getId());
        $this->assertEquals($state->getId(), $state_copy->getId());

        $entityManager->remove($state);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->remove($chatMessage);
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

        $state = new State();
        $state->setMessage($chatMessage);
        $state->setState(2);
        $state->setCreationDatetime(new DateTime());
        $entityManager->persist($state);
        $entityManager->flush();

        $this->assertNotNull($state->getId());

        $entityManager->remove($state);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->remove($chatMessage);
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

        $state = new State();
        $state->setMessage($chatMessage);
        $state->setState(2);
        $state->setCreationDatetime(new DateTime());
        $entityManager->persist($state);
        $entityManager->flush();

        $entityManager->remove($state);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->remove($chatMessage);
        $entityManager->flush();
        $this->assertNull($state->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $invitationLinkRepository = StateRepository::getRepository();

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

        $state = new State();
        $state->setMessage($chatMessage);
        $state->setState(2);
        $state->setCreationDatetime(new DateTime());
        $entityManager->persist($state);
        $entityManager->flush();
        $state->setState(3);
        $entityManager->flush();
        /**
         * @var State $state_copy
         */
        $state_copy = $invitationLinkRepository->find($state->getId());
        $this->assertEquals(3, $state_copy->getState());

        $entityManager->remove($state);
        $entityManager->remove($user);
        $entityManager->remove($event);
        $entityManager->remove($chatMessage);
        $entityManager->flush();
    }

}

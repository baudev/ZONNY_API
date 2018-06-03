<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 16:27
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Event\Event;
use ZONNY\Models\Event\EventRequest;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Event\EventRequestRepository;
use ZONNY\Utils\Database;

class EventRequestTest extends TestCase
{
    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $eventRequestRepository = EventRequestRepository::getRepository();

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
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);

        $eventRequest = new EventRequest();
        $eventRequest->setEvent($event);
        $eventRequest->setUser($user);
        $eventRequest->setResponse(0);
        $eventRequest->setCreationDatetime(new DateTime());
        $entityManager->persist($eventRequest);
        $entityManager->flush();
        /**
         * @var EventRequest $eventRequest_copy
         */
        $eventRequest_copy = $eventRequestRepository->find($eventRequest->getId());
        $this->assertEquals($eventRequest->getId(), $eventRequest_copy->getId());

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

        $event = new Event();
        $event->setCreator($user);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);

        $eventRequest = new EventRequest();
        $eventRequest->setEvent($event);
        $eventRequest->setUser($user);
        $eventRequest->setResponse(0);
        $eventRequest->setCreationDatetime(new DateTime());
        $entityManager->persist($eventRequest);
        $entityManager->flush();

        $this->assertNotNull($eventRequest->getId());

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

        $event = new Event();
        $event->setCreator($user);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);

        $eventRequest = new EventRequest();
        $eventRequest->setEvent($event);
        $eventRequest->setUser($user);
        $eventRequest->setResponse(0);
        $eventRequest->setCreationDatetime(new DateTime());
        $entityManager->persist($eventRequest);
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($eventRequest->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $eventRequestRepository = EventRequestRepository::getRepository();

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
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);

        $eventRequest = new EventRequest();
        $eventRequest->setEvent($event);
        $eventRequest->setUser($user);
        $eventRequest->setResponse(0);
        $eventRequest->setCreationDatetime(new DateTime());
        $entityManager->persist($eventRequest);
        $entityManager->flush();
        $eventRequest->setResponse(1);
        $entityManager->flush();
        /**
         * @var EventRequest $eventRequest_copy
         */
        $eventRequest_copy = $eventRequestRepository->find($eventRequest->getId());
        $this->assertEquals(1, $eventRequest_copy->getResponse());

        $entityManager->remove($user);
        $entityManager->flush();
    }
}

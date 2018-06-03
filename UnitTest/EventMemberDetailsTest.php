<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 03/06/2018
 * Time: 16:17
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Event\Event;
use ZONNY\Models\Event\EventMemberDetails;
use PHPUnit\Framework\TestCase;
use ZONNY\Repositories\Event\EventMemberDetailsRepository;
use ZONNY\Utils\Database;

class EventMemberDetailsTest extends TestCase
{
    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $eventMemberDetailsRepository = EventMemberDetailsRepository::getRepository();

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
        $entityManager->flush();

        $eventMemberDetails = new EventMemberDetails();
        $eventMemberDetails->setEvent($event);
        $eventMemberDetails->setInvitedFriend($user);
        $eventMemberDetails->setIsCreator(true);
        $eventMemberDetails->setCreationDatetime(new DateTime());
        $entityManager->persist($eventMemberDetails);
        $entityManager->flush();
        /**
         * @var EventMemberDetails $eventMemberDetails_copy
         */
        $eventMemberDetails_copy = $eventMemberDetailsRepository->find($eventMemberDetails->getId());
        $this->assertEquals($eventMemberDetails->getId(), $eventMemberDetails_copy->getId());

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
        $entityManager->flush();

        $eventMemberDetails = new EventMemberDetails();
        $eventMemberDetails->setEvent($event);
        $eventMemberDetails->setInvitedFriend($user);
        $eventMemberDetails->setIsCreator(true);
        $eventMemberDetails->setCreationDatetime(new DateTime());
        $entityManager->persist($eventMemberDetails);
        $entityManager->flush();
        $eventMemberDetails->setIsCreator(false);
        $entityManager->flush();

        $this->assertNotNull($eventMemberDetails->getId());

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
        $entityManager->flush();

        $eventMemberDetails = new EventMemberDetails();
        $eventMemberDetails->setEvent($event);
        $eventMemberDetails->setInvitedFriend($user);
        $eventMemberDetails->setIsCreator(true);
        $eventMemberDetails->setCreationDatetime(new DateTime());
        $entityManager->persist($eventMemberDetails);
        $entityManager->flush();
        $eventMemberDetails->setIsCreator(false);
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($eventMemberDetails->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $eventMemberDetailsRepository = EventMemberDetailsRepository::getRepository();

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
        $entityManager->flush();

        $eventMemberDetails = new EventMemberDetails();
        $eventMemberDetails->setEvent($event);
        $eventMemberDetails->setInvitedFriend($user);
        $eventMemberDetails->setIsCreator(true);
        $eventMemberDetails->setCreationDatetime(new DateTime());
        $entityManager->persist($eventMemberDetails);
        $entityManager->flush();
        $eventMemberDetails->setIsCreator(false);
        $entityManager->flush();
        /**
         * @var EventMemberDetails $eventMemberDetails_copy
         */
        $eventMemberDetails_copy = $eventMemberDetailsRepository->find($eventMemberDetails->getId());
        $this->assertFalse($eventMemberDetails_copy->getisCreator());

        $entityManager->remove($user);
        $entityManager->flush();
    }

}

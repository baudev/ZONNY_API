<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 01/06/2018
 * Time: 22:21
 */

namespace ZONNY\UnitTest;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';

use DateTime;
use ZONNY\Models\Account\User;
use ZONNY\Models\Event\Event;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Suggestion\Suggestion;
use ZONNY\Repositories\Event\EventRepository;
use ZONNY\Utils\Database;
use ZONNY\Utils\DatetimeISO8601;

class EventTest extends TestCase
{

    public function testProcurement(){
        $entityManager = Database::getEntityManager();
        $eventRepository = EventRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $event = new Event();
        $event->setCreator($user);
        $event->setFromSuggestion($suggestion);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);
        $entityManager->flush();
        /**
         * @var Event $event_copy
         */
        $event_copy = $eventRepository->find($event->getId());
        $this->assertEquals($event->getId(), $event_copy->getId());

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

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $event = new Event();
        $event->setCreator($user);
        $event->setFromSuggestion($suggestion);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);
        $entityManager->flush();

        $this->assertNotNull($event->getId());

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

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $event = new Event();
        $event->setCreator($user);
        $event->setFromSuggestion($suggestion);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);
        $entityManager->flush();

        $entityManager->remove($user);
        $entityManager->flush();
        $this->assertNull($event->getId());
    }

    public function testModification(){
        $entityManager = Database::getEntityManager();
        $eventRepository = EventRepository::getRepository();

        $user = new User();
        $user->setName("user1");
        $user->setKeyApp("key1");
        $user->setPlatform(1);
        $user->setCreationDatetime(new DateTime());
        $entityManager->persist($user);

        $suggestion = new Suggestion();
        $suggestion->setCreator($user);
        $suggestion->setLatitude(0.0);
        $suggestion->setLongitude(0.0);
        $suggestion->setIsRecurrent(false);
        $suggestion->setAnonymousCreator(false);
        $suggestion->setCreationDatetime(new DateTime());
        $entityManager->persist($suggestion);

        $event = new Event();
        $event->setCreator($user);
        $event->setFromSuggestion($suggestion);
        $event->setLatitude(0.0);
        $event->setLongitude(0.0);
        $event->setStartDatetime(new DateTime());
        $event->setEndDatetime(new DateTime());
        $event->setWithLocation(true);
        $event->setIsPublic(true);
        $event->setCreationDatetime(new Datetime());
        $entityManager->persist($event);
        $entityManager->flush();
        $event->setIsPublic(false);
        $entityManager->flush();
        /**
         * @var Event $event_copy
         */
        $event_copy = $eventRepository->find($event->getId());
        $this->assertFalse($event_copy->getisPublic());

        $entityManager->remove($user);
        $entityManager->flush();
    }

}

<?php
namespace ZONNY\UnitTest;

use ZONNY\Models\Accounts\User;
use ZONNY\Models\Events\Event;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Utils\Application;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class EventTest extends TestCase
{

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetHistoric()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on vérifie que l'historique ne contient aucun élément
        $this->assertEquals(0, count(Event::getHistoric($user, 1)));

        // on invite l'utilisateur a son évènement maintenant
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        $historic = Event::getHistoric($user, 1);
        // on vérifie que l'historique contient un élement
        $this->assertEquals(1, count($historic));
        // on vérifie que l'élement contient les informations concernant l'évènement plus la réponse de l'utilisateur
        $this->assertEquals(2, count($historic[0]));
        $this->assertArrayHasKey("event", $historic[0]);
        $this->assertArrayHasKey("event_member_details", $historic[0]);

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     * @throws \Exception
     */
    public function testGetPercentageRemaining()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $current = new \DateTime();
        $event->setStartTime($current->format("Y-m-d H:i:s"));
        // tomorrow
        $tomorrow = new \DateTime();
        $tomorrow->add(new \DateInterval('P1D'));
        $event->setEndTime($tomorrow->format('Y-m-d H:i:s'));
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateur a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        // on vérifie que le pourcentage est de 100 %
        $this->assertEquals(100, $event->getPercentageRemaining());

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetNumberParticipants()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateur a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->setResponse(EventMemberDetails::HE_COMES);
        $event_member_details->addToDataBase();

        // on vérifie que le nombre d'invité est 1
        $this->assertEquals(1, $event->getNumberParticipants());

        // on supprime l'utilisateur des invités
        $event_member_details->deleteFromDataBase();
        // on vérifie que le nombre d'invité est 0;
        $this->assertEquals(0, $event->getNumberGuests());

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
    }

    public function testGetArrayParticipantsFromString()
    {
        $invited = "15, 23";
        $array = Event::getArrayParticipantsFromString($invited, null);
        $this->assertEquals(2, count($array));
        $this->assertEquals(15, $array[0]);
        $this->assertEquals(23, $array[1]);

        $invited = "15, 23,";
        $array = Event::getArrayParticipantsFromString($invited, null);
        $this->assertEquals(2, count($array));
        $this->assertEquals(15, $array[0]);
        $this->assertEquals(23, $array[1]);
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetAllGuests()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on vérifie que l'évènement ne contient aucun élément car il n'a aucun invité
        $this->assertEquals(0, count($event->getAllGuests(false)));

        // on invite l'utilisateur a son évènement maintenant
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        $guest = $event->getAllGuests(true);
        // on vérifie que l'historique contient un élement
        $this->assertEquals(1, count($guest));
        // on vérifie que l'élement contient les informations concernant l'évènement plus la réponse de l'utilisateur
        $this->assertEquals(2, count($guest[0]));
        $this->assertArrayHasKey("friend", $guest[0]);
        $this->assertArrayHasKey("event_member_details", $guest[0]);

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     * @throws \Exception
     */
    public function testIsOver()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateur a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        // on vérifie que l'évènement est terminé
        $this->assertTrue($event->isOver());

        // on modifie la date de fin pour que l'évènement ne soit pas terminé
        $tomorrow = new \DateTime();
        $tomorrow->add(new \DateInterval('P2D'));
        $event->setEndTime($tomorrow->format('Y-m-d H:i:s'));
        $event->updateToDataBase();

        // on vérifie que l'évènement n'est pas terminé
        $this->assertFalse($event->isOver());

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testUpdateToDataBase()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateur a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        // on update les informations
        $event->setName("NAME2");
        $event->setPublic(Event::IS_NOT_PUBLIC);
        $event->updateToDataBase();

        // on vérifie que l'update s'est correctement fait
        $event->getFromDatabase();
        $this->assertEquals("NAME2", $event->getName());
        $this->assertEquals(Event::IS_NOT_PUBLIC, $event->getPublic());

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testAddToDataBase()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateur a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        // on essaie de récupérer l'évènement par son indentifiant
        $new_event = new Event();
        $new_event->setId($event->getId());
        $this->assertTrue($new_event->getFromDatabase());

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testDeleteFromDataBase()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();
        // on le supprime de la base de données
        $event->deleteFromDataBase();

        // on essaie de récupérer l'évènement par son indentifiant
        $new_event = new Event();
        $new_event->setId($event->getId());
        $this->assertFalse($new_event->getFromDatabase());
        $user->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetFromDatabase()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateuer a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        // on tente de le récupérer depuis la base de données
        $this->assertTrue($event->getFromDatabase());

        $user->deleteFromDataBase();
        $event_member_details->deleteFromDataBase();
        $event->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetNumberGuests()
    {
        $user = new User();
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("797889");
        $user->setExpire("2018-02-01 22:02:02");
        $user->addToDataBase();
        Application::setUser($user);

        // on crée un évènement
        $event = new Event();
        $event->setName("NAME");
        $event->setLatitude(45.52);
        $event->setLongitude(2.352);
        $event->setStartTime("2018-02-01 01:01:01");
        $event->setEndTime("2018-02-02 01:01:01");
        $event->setPictureUrl(null);
        $event->setWithLocalisation(0);
        $event->setPublic(Event::IS_PUBLIC);
        $event->setCreatorId(211);
        $event->addToDataBase();

        // on invite l'utilisateur a son évènement
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId($event->getId());
        $event_member_details->setInvitedFriendId($user->getId());
        $event_member_details->addToDataBase();

        // on vérifie que le nombre d'invité est 1
        $this->assertEquals(1, $event->getNumberGuests());

        // on supprime l'utilisateur des invités
        $event_member_details->deleteFromDataBase();
        // on vérifie que le nombre d'invité est 0;
        $this->assertEquals(0, $event->getNumberGuests());

        $user->deleteFromDataBase();
        $event->deleteFromDataBase();
    }
}

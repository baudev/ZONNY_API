<?php
namespace ZONNY\UnitTest;

use ZONNY\Models\Events\EventMemberDetails;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class EventMemberDetailsTest extends TestCase
{

    /**
     *
     * @throws \ZONNY\Utils\PublicError
     */
    public function testDeleteFromDataBase()
    {

        // on crée l'invitation
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId(1);
        $event_member_details->setInvitedFriendId(1);
        $event_member_details->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
        $event_member_details->addToDataBase();
        $event_member_details->deleteFromDataBase();

        // on vérifie si on peut le récupérer
        $this->assertFalse($event_member_details->getFromDatabase());

    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testUpdateToDataBase()
    {
        // on crée l'invitation
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId(1);
        $event_member_details->setInvitedFriendId(1);
        $event_member_details->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
        $event_member_details->addToDataBase();
        $event_member_details->setResponse(EventMemberDetails::HE_COMES);
        $event_member_details->updateToDataBase();
        $event_member_details->getFromDatabase();
        $this->assertEquals(EventMemberDetails::HE_COMES, $event_member_details->getResponse());
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testAddToDataBase()
    {
        // on crée l'invitation
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId(1);
        $event_member_details->setInvitedFriendId(1);
        $event_member_details->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
        $event_member_details->addToDataBase();
        $this->assertTrue($event_member_details->getFromDatabase());
        $event_member_details->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetFromDatabase()
    {
        // on crée l'invitation
        $event_member_details = new EventMemberDetails();
        $event_member_details->setEventId(1);
        $event_member_details->setInvitedFriendId(1);
        $event_member_details->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
        $event_member_details->addToDataBase();
        $this->assertTrue($event_member_details->getFromDatabase());
        $this->assertEquals(EventMemberDetails::HAS_NOT_ANSWERERD, $event_member_details->getResponse());
        $event_member_details->deleteFromDataBase();
    }
}

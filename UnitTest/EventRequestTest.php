<?php
namespace ZONNY\UnitTest;

use ZONNY\Models\Events\EventRequest;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class EventRequestTest extends TestCase
{

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetFromDatabase()
    {
        // on crée une requête
        $request = new EventRequest();
        $request->setEventId(1);
        $request->setFriendId(1);
        $request->setResponse(EventRequest::HAS_NOT_ANSWERED_YET);
        $request->addToDataBase();
        $this->assertTrue($request->getFromDatabase());
        $this->assertEquals(1, $request->getEventId());
        $request->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testAddToDataBase()
    {
        // on crée une requête
        $request = new EventRequest();
        $request->setEventId(1);
        $request->setFriendId(1);
        $request->setResponse(EventRequest::HAS_NOT_ANSWERED_YET);
        $request->addToDataBase();
        $request->getFromDatabase();
        $this->assertEquals(1, $request->getEventId());
        $request->deleteFromDataBase();
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testDeleteFromDataBase()
    {
        // on crée une requête
        $request = new EventRequest();
        $request->setEventId(1);
        $request->setFriendId(1);
        $request->setResponse(EventRequest::HAS_NOT_ANSWERED_YET);
        $request->addToDataBase();
        $request->deleteFromDataBase();
        $this->assertFalse($request->getFromDatabase());
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testUpdateToDataBase()
    {
        // on crée une requête
        $request = new EventRequest();
        $request->setEventId(1);
        $request->setFriendId(1);
        $request->setResponse(EventRequest::HAS_NOT_ANSWERED_YET);
        $request->addToDataBase();
        $request->setEventId(2);
        $request->updateToDataBase();
        $request->getFromDatabase();
        $this->assertEquals(2, $request->getEventId());
        $request->deleteFromDataBase();
    }
}

<?php

namespace ZONNY\Controllers\Events;


use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventRequest;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\HackAttempts;
use ZONNY\Utils\PublicError;

class GetAllEventRequests implements \JsonSerializable
{

    private $_event_id;
    private $_page;

    /**
     * @SWG\Get(
     *     path="/event/request/{event_id}/{page}",
     *     summary="Get all requests of an event",
     *     tags={"event"},
     *     description="Allow the creator of a public event to get all requests concerning an event.",
     *     operationId="getAllEventRequests",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the event.",
     *         in="path",
     *         name="event_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Page number. Start to 1",
     *         in="path",
     *         name="page",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="object", properties={
     *                   @SWG\Property(property="request", type="object", ref="#/definitions/EventRequest"),
     *                   @SWG\Property(property="friend", type="object", ref="#/definitions/GoodFriend"),
     *             }),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="error during request",
     *         @SWG\Items(ref="#/definitions/Error"),
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     */


    /**
     * GetAllEventRequests constructor.
     * @param $event_id
     * @param $page
     * @throws \ZONNY\Utils\PublicError
     */
    public function __construct($event_id, $page)
    {
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format.", ErrorCode::INVALID_EVENT_ID);
        Application::check_variables($page, true, true, "^[0-9]+$", "Invalid page parameter format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        // on récupère les informations concernant l'évènement
        $event = new Event();
        $event->setId($event_id);
        if($event->getFromDatabase()){
            // on vérifie que l'utilisateur est bien le créateur
            if($event->getCreatorId()!=Application::getUser()->getId()){
                // on retient la tentative de piratage
                new HackAttempts("get/events/request/".$event->getId(), Application::getUser());
                throw new PublicError("You're not the creator of this event. You can't show it's requests.", ErrorCode::EVENT_NOT_CREATOR);
            }
            else {
                $this->setEventId($event_id);
                $this->setPage($page);
            }
        }
        else {
            if($event->is_authorized){
                throw new PublicError("This event does'nt seem to exist anymore.", ErrorCode::EVENT_DOESNT_EXIST_ANYMORE);
            }else {
                // on retient la tentative de piratage
                new HackAttempts("get/events/request/".$event->getId(), Application::getUser());
                throw new PublicError("You're not the creator of this event. You can't show it's requests.", ErrorCode::EVENT_NOT_CREATOR);
            }
        }
    }


    /**
     * @return array|mixed|null
     * @throws PublicError
     */
    public function jsonSerialize()
    {
        $request = new EventRequest();
        $request->setEventId($this->getEventId());
        $all_requests = $request->getAllEventRequests($this->getPage(), true);
        return $all_requests;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->_event_id;
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id): void
    {
        $this->_event_id = $event_id;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->_page = $page;
    }

}
<?php
namespace ZONNY\Controllers\Events;

use ZONNY\Models\Events\Event;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class GetEvent implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/event/{event_id}",
     *     summary="Get all information concerning the event",
     *     tags={"event"},
     *     description="Return all information concerning the event.",
     *     operationId="getEvent",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the event.",
     *         in="path",
     *         name="event_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Items(type="object", properties={
     *                   @SWG\Property(property="event", type="object", ref="#/definitions/Event"),
     *                   @SWG\Property(property="guests", type="array",
     *                      @SWG\Items(type="object", properties={
     *                          @SWG\Property(property="friend", type="object", ref="#/definitions/NormalFriend"),
     *                          @SWG\Property(property="event_member_details", type="object", ref="#/definitions/EventMemberDetails")
     *                          })
     *                      )
     *             }),
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

    private $_event;
    private $_guests;

    /**
     * GetEvent constructor.
     * @param $event_id
     * @throws \ZONNY\Utils\PublicError
     */
    public function __construct($event_id)
    {
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format.", ErrorCode::INVALID_EVENT_ID);

        // on récupère les informations sur l'évènement
        $event = new Event();
        $event->setId($event_id);
        if($event->getFromDatabase()){
            $this->setEvent($event);
            // si l'utilisateur est autorisé à afficher les informations concernant l'évènement
            if($event->_is_authorized){
                $guests = $event->getAllGuests(true);
                $this->setGuests($guests);
            }
        }else {
            throw new PublicError("Vous n'êtes pas autorisé à afficher cet évènement.", ErrorCode::NOT_AUTHORIZED_SEE_EVENT);
        }
    }


    public function jsonSerialize()
    {
        return array('event' => $this->getEvent(), 'guests' => $this->getGuests());
    }


    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event): void
    {
        $this->_event = $event;
    }

    /**
     * @return mixed
     */
    public function getGuests()
    {
        return $this->_guests;
    }

    /**
     * @param mixed $guests
     */
    public function setGuests($guests): void
    {
        $this->_guests = $guests;
    }


}
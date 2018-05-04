<?php

namespace ZONNY\Controllers\Events;

use ZONNY\Models\Accounts\Friend;
use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Models\Push\PushNotification;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class DeleteEvent implements \JsonSerializable
{


    /**
     * @SWG\Delete(
     *     path="/event/{event_id}",
     *     summary="Delete an event",
     *     tags={"event"},
     *     description="Allow the user to delete one of his events.",
     *     operationId="deleteEvent",
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
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="response", type="string", example="ok")
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
     * DeleteEvent constructor.
     * @param $event_id
     * @throws \ZONNY\Utils\PublicError
     */
    public function __construct($event_id)
    {
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format.", ErrorCode::INVALID_EVENT_ID);
        // on vérifie que l'utilisateur est bien le créateur de l'évènement
        $event = new Event();
        $event->setId($event_id);
        if($event->getFromDatabase()){
            if($event->getCreatorId()!=Application::getUser()->getId()){
                throw new PublicError("You can't delete this event. You're not the creator.", ErrorCode::EVENT_NOT_CREATOR);
            }
            else {
                // l'utilisateur est bien le créateur
                // on récupère tous les invités à l'évènement
                $all_guest = $event->getAllGuests(true);
                foreach ($all_guest as $event_member_detail){
                    // on convertie le tableau en object EventMemberDetails
                    /** @var  $event_member_detail EventMemberDetails */
                    $event_member_detail = (object)$event_member_detail['event_member_details'];
                    $event_member_detail->deleteFromDataBase();
                    // on supprime la notification push de tous les invités qui n'avaient pas encore répondu
                    if($event_member_detail->getResponse()==EventMemberDetails::HAS_NOT_ANSWERERD){
                        $friend = new Friend();
                        $friend->setId($event_member_detail->getInvitedFriendId());
                        if($friend->getFromDatabase()){
                            PushNotification::delete_notification($event->getId(), PushNotification::NEW_EVENT_INVITATION, $friend);
                        }
                    }
                }
                // on supprime l'évènement
                $event->deleteFromDataBase();
            }
        }
        else {
            if($event->is_authorized){
                throw new PublicError("This event does'nt seem to exist anymore.", ErrorCode::EVENT_DOESNT_EXIST_ANYMORE);
            }else {
                throw new PublicError("You can't delete this event. You're not the creator.", ErrorCode::EVENT_NOT_CREATOR);
            }
        }
    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }

}
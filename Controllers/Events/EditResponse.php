<?php

namespace ZONNY\Controllers\Events;

use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class EditResponse implements \JsonSerializable
{

    /**
     * @SWG\Put(
     *     path="/event/response",
     *     summary="Respond to an event",
     *     tags={"event"},
     *     description="Allow the user to respond to an event.",
     *     operationId="answerEvent",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the event.",
     *         in="formData",
     *         name="event_id",
     *         required=true,
     *         type="integer"
     *     ),
     *      @SWG\Parameter(
     *         description="Response of the user concerning the event. 1 the user is coming. Otherwise, 0.",
     *         in="formData",
     *         name="response",
     *         required=true,
     *         type="integer",
     *         enum={1,2}
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
     * EditResponse constructor.
     * @param $event_id
     * @param $response
     * @throws \ZONNY\Utils\PublicError
     */
    public function __construct($event_id, $response)
    {
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format.", ErrorCode::INVALID_EVENT_ID);
        Application::check_variables($response, true, true, "^[1-2]$", "Invalid response parameter format.", ErrorCode::INVALID_RESPONSE_FORMAT);
        // on vérifie si l'utilisateur est invité
        $event_member_detail = new EventMemberDetails();
        $event_member_detail->setEventId($event_id);
        $event_member_detail->setInvitedFriendId(Application::getUser()->getId());
        if($event_member_detail->getFromDatabase()){
            // on vérifie si l'utilisateur n'est pas le créateur
            if($event_member_detail->getCreator()){
                throw new PublicError("You're the creator of this event. You can't respond to it. You're necessarily comming.");
            }
            // on vérifie si l'évènemement n'est pas terminé
            $event = new Event();
            $event->setId($event_id);
            if($event->getFromDatabase()){
                if($event->isOver()){
                    throw new PublicError("The event has ended. You can't edit your response anymore.", ErrorCode::EVENT_HAS_ENDED);
                }
                else {
                    $event_member_detail->setResponse($response);
                    $event_member_detail->updateToDataBase();
                }
            }
            else {
                // c'est nécessairement que l''évènement n'existe plus car l'utilisateur a l'autorisation de le voir...
                throw new PublicError("This event does'nt seem to exist anymore.", ErrorCode::EVENT_DOESNT_EXIST_ANYMORE);
            }
        }
        else {
            throw new PublicError("You're not a guest of this event.", ErrorCode::EVENT_NOT_INVITED);
        }
    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }

}
<?php
namespace ZONNY\Controllers\Events;


use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Models\Events\EventRequest;
use ZONNY\Models\Account\Friend;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\HackAttempts;
use ZONNY\Utils\PublicError;

class EditEventRequest implements \JsonSerializable
{

    /**
     * @SWG\Put(
     *     path="/event/request",
     *     summary="Answer to a request",
     *     tags={"event"},
     *     description="Allow the creator of a public event to respond to a request.",
     *     operationId="editEventRequest",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the request.",
     *         in="formData",
     *         name="request_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Answer to the request. 1 the creator accepts it. 2 the creator ignores it.",
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
     * EditEventRequest constructor.
     * @param $request_id
     * @param $response
     * @throws PublicError
     */
    public function __construct($request_id, $response)
    {
        Application::check_variables($request_id, true, true, "^[0-9]+$", "Invalid request_id format.", ErrorCode::INVALID_EVENT_ID);
        Application::check_variables($response, true, true, "^[1-2]$", "Invalid response format.", ErrorCode::INVALID_RESPONSE_FORMAT);
        // on essaie de récupérer la requete
        $event_request = new EventRequest();
        $event_request->setId($request_id);
        $event_request->setFriendId(Application::getUser()->getId());
        if($event_request->getFromDatabase()){
            // on vérifie si le créateur de l'évènement associé
            $event = new Event();
            $event->setId($event_request->getEventId());
            if($event->getFromDatabase()){
                if($event->getCreatorId()==Application::getUser()->getId()){
                    // l'utilisateur est bien le créateur
                    if($response==EventRequest::HAS_RESPONDED_TRUE){
                        // le créateur accepte la demande, on ajoute donc l'ami à l'évènement et on supprime la demande
                        $friend = new Friend();
                        $friend->setId($event_request->getFriendId());
                        if($friend->getFromDatabase()) {
                            // ajout à l'évènement
                            $event_member_details = new EventMemberDetails();
                            $event_member_details->setEventId($event->getId());
                            $event_member_details->setInvitedFriendId($friend->getId());
                            $event_member_details->setFriendLatitude($friend->getLatitude());
                            $event_member_details->setFriendLongitude($friend->getLongitude());
                            $event_member_details->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
                            $event_member_details->setCreator(EventMemberDetails::IS_NOT_CREATOR);
                            $event_member_details->addToDataBase();
                            // suppression de la demande
                            $event_request->deleteFromDataBase();
                        }
                        else {
                            // cas assez étrange
                            throw new PublicError("Impossible to invite this friend to your event. The account of your friend doesn't seem to exist anymore. If the problem persists, contact us.", ErrorCode::PASS_TEXT);
                        }
                    }
                    elseif ($response==EventRequest::HAS_RESPONDED_FALSE){
                        // on update la réponse de la demande
                        $event_request->setResponse(EventRequest::HAS_RESPONDED_FALSE);
                        $event_request->updateToDataBase();
                    }
                }
                else {
                    // on retient la tentative de piratage
                    new HackAttempts("put/events/request/".$event->getId(), Application::getUser());
                    throw new PublicError("You're not the creator of this event. You can't answer to it's requests.", ErrorCode::EVENT_NOT_CREATOR);
                }
            }
            else{
                if($event->is_authorized){
                    throw new PublicError("This event does'nt seem to exist anymore.", ErrorCode::EVENT_DOESNT_EXIST_ANYMORE);
                }else {
                    // on retient la tentative de piratage
                    new HackAttempts("put/events/request/".$event->getId(), Application::getUser());
                    throw new PublicError("You're not the creator of this event. You can't answer to it's requests.", ErrorCode::EVENT_NOT_CREATOR);
                }
            }
        }
        else{
            throw new PublicError("The event's request doesn't seem to exist anymore.", ErrorCode::EVENT_REQUEST_NOT_FOUND);
        }



    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }

}
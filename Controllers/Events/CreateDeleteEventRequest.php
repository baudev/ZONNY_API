<?php

namespace ZONNY\Controllers\Events;


use ZONNY\Models\Accounts\Friend;
use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Models\Events\EventRequest;
use ZONNY\Models\Push\PushNotification;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class CreateDeleteEventRequest
{

    /**
     * @SWG\Post(
     *     path="/event/request",
     *     summary="Create a request",
     *     tags={"event"},
     *     description="Allow to the user asking his friend if he can comes to a public event created by the latter.",
     *     operationId="createEventRequest",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the event.",
     *         in="formData",
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
     * @param $event_id
     * @throws \ZONNY\Utils\PublicError
     */
    public function createRequest($event_id){
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format.", ErrorCode::INVALID_EVENT_ID);
        // on recupère les informations concernant l'évènement
        $event = new Event();
        $event->setId($event_id);
        if($event->getFromDatabase()){
            // le créateur de l'évènement est bien un bon ami de l'utilisateur et l'évènement est public
            // on vérifie que l'utilisateur n'est pas déjà invité
            $event_member_details = new EventMemberDetails();
            $event_member_details->setEventId($event->getId());
            $event_member_details->setInvitedFriendId(Application::getUser()->getId());
            if(!$event_member_details->getFromDatabase()){
                //l'utilisateur n'est pas déjà invité
                // on vérifie que l'évènement n'est pas terminé
                if(!$event->isOver()){
                    // on vérifie si l'utilisateur n'a pas déjà envoyé une demande
                    $event_request = new EventRequest();
                    $event_request->setEventId($event->getId());
                    $event_request->setFriendId(Application::getUser()->getId());
                    if(!$event_request->getFromDatabase()){
                        // l'utilisateur n'a pas déjà demandé à venir
                        // le créateur de l'évènement est un bon ami étant donné qu'on a accès aux données de l'évènement
                        $event_request->setResponse(EventRequest::HAS_NOT_ANSWERED_YET);
                        $event_request->addToDataBase();
                        // on envoie une notification au créateur de l'évènement
                        $friend = new Friend();
                        $friend->setId($event->getCreatorId());
                        if($friend->getFromDatabase()) {
                            PushNotification::generate_new_request_push($friend, $event, $event_request);
                        }
                        return array("response" => "ok");
                    }
                    else {
                        throw new PublicError("You have already sent a request for this event.", ErrorCode::EVENT_REQUEST_ALREADY_SENT);
                    }
                }
                else {
                    throw new PublicError("Events has ended. You can't ask to come.", ErrorCode::EVENT_HAS_ENDED);
                }
            }
            else {
                // l'utilisateur est déà invité
                throw new PublicError("You're already a guest of this event.", ErrorCode::EVENT_REQUEST_ALREADY_INVITED);
            }
        }
        else {
            if($event->is_authorized){
                throw new PublicError("This event does'nt seem to exist anymore.", ErrorCode::EVENT_DOESNT_EXIST_ANYMORE);
            }else {
                throw new PublicError("The creator of this event isn't your friend or this event isn't public. You can't ask to come.", ErrorCode::EVENT_IMPOSSIBLE_OPERATION);
            }
        }
    }

    /**
     * @SWG\Delete(
     *     path="/event/request/{event_id}",
     *     summary="Delete a request",
     *     tags={"event"},
     *     description="Allow to the user to delete his request to this event.",
     *     operationId="deleteEventRequest",
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
     * @param $event_id
     * @return array
     * @throws \ZONNY\Utils\PublicError
     */
    public function deleteRequest($event_id){
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format.", ErrorCode::INVALID_EVENT_ID);
        // on vérifie si la demande existe avant de la supprimer
        $event_request = new EventRequest();
        $event_request->setEventId($event_id);
        $event_request->setFriendId(Application::getUser()->getId());
        if($event_request->getFromDatabase()){
            // la requete existe
            // on la supprime
            $event_request->deleteFromDataBase();
            // si la réponse à l'invitation n'était toujours pas donnée, on supprime la notification push auprès du créateur
            if($event_request->getResponse()==EventRequest::HAS_NOT_ANSWERED_YET){
                // on récupère l'identifiant du créateur de l'évènement
                $event = new Event();
                $event->setId($event_request->getEventId());
                if($event->getFromDatabase()){
                    $friend = new Friend();
                    $friend->setId($event->getCreatorId());
                    if($friend->getFromDatabase()){
                        PushNotification::delete_notification($event_request->getId(), PushNotification::NEW_EVENT_REQUEST, $friend);
                    }
                }
            }
            return array("response" => "ok");
        }
        else {
            // la demande n'existe pas
            throw new PublicError("Impossible to delete the event's request because it doesn't exist.", ErrorCode::EVENT_REQUEST_NOT_FOUND);
        }
    }

}
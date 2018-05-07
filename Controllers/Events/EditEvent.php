<?php
namespace ZONNY\Controllers\Events;

use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Models\Accounts\Friend;
use ZONNY\Models\Accounts\FriendLink;
use ZONNY\Models\Push\PushNotification;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\HackAttempts;
use ZONNY\Utils\PublicError;

class EditEvent implements \JsonSerializable
{

    /**
     * @SWG\Put(
     *     path="/event",
     *     summary="Edit an event",
     *     tags={"event"},
     *     description="Allow the user to edit his event.",
     *     operationId="editEvent",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the event which the user wants to edit.",
     *         in="formData",
     *         name="event_id",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Desired name of the event.",
     *         in="formData",
     *         name="name",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="If the event is public or not. 1 if true. 0 if false. If the event is public, all creator's friends would be able to see some information about the event.",
     *         in="formData",
     *         name="public",
     *         required=false,
     *         type="integer",
     *         enum={0,1}
     *     ),
     *     @SWG\Parameter(
     *         description="Latitude of the event. If null, the latitude will be the equidistant point between all guests (including the creator).",
     *         in="formData",
     *         name="latitude",
     *         required=false,
     *         type="number",
     *         format="float"
     *     ),
     *     @SWG\Parameter(
     *         description="Longitude of the event. If null, the latitude will be the equidistant point between all guests (including the creator).",
     *         in="formData",
     *         name="longitude",
     *         required=false,
     *         type="integer",
     *         format="float"
     *     ),
     *     @SWG\Parameter(
     *         description="Datetime of the event's start. The datetime must be in the next 24h.",
     *         in="formData",
     *         name="start_time",
     *         required=false,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Datetime of the event's end. The datetime must be in the next 24h after the event's start.",
     *         in="formData",
     *         name="end_time",
     *         required=false,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="The creator can give more information concerning the event.",
     *         in="formData",
     *         name="information",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="The picture of the event. If null, it will be replaced by a default one.",
     *         in="formData",
     *         name="picture_url",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="The guests'ids separated by coma. Example: 1378,15256,145",
     *         in="formData",
     *         name="invited_friends_id",
     *         required=false,
     *         type="string"
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
     * EditEvent constructor.
     * @param $event_id
     * @param $name
     * @param $public
     * @param $latitude
     * @param $longitude
     * @param $start_time
     * @param $end_time
     * @param $informations
     * @param $picture_url
     * @param $invited_friends_id
     * @throws PublicError
     */
    public function __construct($event_id, $name, $public, $latitude, $longitude, $start_time, $end_time, $informations, $picture_url, $invited_friends_id)
    {
        Application::check_variables($event_id, true, true, "^[0-9]+$", "Invalid event_id format", ErrorCode::INVALID_EVENT_ID);
        // on vérifie si l'utilisateur est le créateur de l'évènement avant de continuer
        $event = new Event();
        $event->setId($event_id);
        if(!$event->getFromDatabase()){
            throw new PublicError("This event doesn't exist or you're not allowed to see it.", ErrorCode::EVENT_IMPOSSIBLE_OPERATION);
        }
        if($event->getCreatorId()!=Application::getUser()->getId()){
            // on note la tentative de piratage
            new HackAttempts("edit/events/".$event_id, Application::getUser());
            throw new PublicError("You're not the creator of this event. You can't edit it.", ErrorCode::EVENT_NOT_CREATOR);
        }
        // on vérifie les autres variables maintenant
        Application::check_variables($name, false, false, null, null, null);
        Application::check_variables($public, false, true, "^[0-1]$", "Invalid public parameter format.", ErrorCode::INVALID_PUBLIC_VARIABLE);
        Application::check_variables($latitude, false, true, "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$", "Latitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        Application::check_variables($longitude, false, true, "^\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$", "Longitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        Application::check_variables($start_time, false, true, "^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$","Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        Application::check_variables($end_time, false, true, "^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$","Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        Application::check_variables($informations, false, false, null, null, null);
        Application::check_variables($picture_url, false, true, "((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;\"':<]|\.\s|$)", "Invalid URL format.", ErrorCode::INVALID_URL);
        Application::check_variables($invited_friends_id, false, true, "^([0-9]+,?)+", "Invited_friend_id variable must be a string separated by coma. Ex: 15,65,2.", ErrorCode::INVALID_VARIABLE_FORMAT);
        // on vérifie maintenant les horaires fournis
        $current_datetime = new \DateTime();
        if (!empty($start_time)) {
            $start_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $start_time);
        }
        if (!empty($end_time)) {
            $end_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $end_time);
        }
        if(!empty($start_time) && !empty($end_time)){
            $diff = $start_time_datetime->getTimestamp() - $current_datetime->getTimestamp();
            if ($diff > MAX_START_EVENT_INTERVAL) {
                throw new PublicError("Events must start in the next 24h.", ErrorCode::EVENT_START_NEXT_24H);
            }
            $diff = $end_time_datetime->getTimestamp() - $start_time_datetime->getTimestamp();
            if ($diff > MAX_EVENT_DURATION) {
                throw new PublicError("Events duration can be only 24max.", ErrorCode::EVENT_DURATION_24H_MAX);
            }
            if ($diff < MIN_EVENT_DURATION) {
                throw new PublicError("Events duration can't be inferior to 1 minute.", ErrorCode::EVENT_DURATION_LESS_1MINUTE);
            }
        }
        elseif (!empty($end_time) && empty($start_time)){
            $end_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $event->getEndTime());
            $diff = $end_time_datetime->getTimestamp() - $start_time_datetime->getTimestamp();
            if ($diff > MAX_EVENT_DURATION) {
                throw new PublicError("Events duration can be only 24max.", ErrorCode::EVENT_DURATION_24H_MAX);
            }
            if ($diff < MIN_EVENT_DURATION) {
                throw new PublicError("Events duration can't be inferior to 1 minute.", ErrorCode::EVENT_DURATION_LESS_1MINUTE);
            }
        }
        elseif (empty($end_time) && !empty($start_time)){
            $start_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $event->getStartTime());
            $diff = $end_time_datetime->getTimestamp() - $start_time_datetime->getTimestamp();
            if ($diff > MAX_START_EVENT_INTERVAL) {
                throw new PublicError("Events must start in the next 24h.", ErrorCode::EVENT_START_NEXT_24H);
            }
            if ($diff > MAX_EVENT_DURATION) {
                throw new PublicError("Events duration can be only 24max.", ErrorCode::EVENT_DURATION_24H_MAX);
            }
            if ($diff < MIN_EVENT_DURATION) {
                throw new PublicError("Events duration can't be inferior to 1 minute.", ErrorCode::EVENT_DURATION_LESS_1MINUTE);
            }
        }

        // on vérifie si l'évènement est terminé ou non
        if($event->isOver()){
            throw new PublicError("Events has ended. You can't edit it.", ErrorCode::EVENT_HAS_ENDED);
        }

        $event->setName($name);
        $event->setPublic($public);
        $event->setLatitude($latitude);
        $event->setLongitude($longitude);
        $event->setStartTime($start_time);
        $event->setEndTime($end_time);
        $event->setInformation($informations);
        $event->setPictureUrl($picture_url);

        // on met à jour l'évènement
        $event->updateToDataBase();

        // on s'occupe de la liste des invités
        if(!empty($invited_friends_id)){
            $invited_friends_id = Event::getArrayParticipantsFromString($invited_friends_id, Application::getUser());
            foreach ($invited_friends_id as $key => $invited_friend_id){
                // on vérifie si l'identifiant fourni correspond à un ami déjà invité ou non
                $event_member_detail = new EventMemberDetails();
                $event_member_detail->setEventId($event->getId());
                $event_member_detail->setInvitedFriendId($invited_friend_id);
                if($event_member_detail->getFromDatabase()){
                    // l'ami était déjà invité à l'évènement
                    // on le supprime des invités
                    $event_member_detail->deleteFromDataBase();
                    // si l'utilisateur n'avait pas encore répondu, la notification push est peut-être encore affichée. On la supprime
                    if($event_member_detail->getResponse()==EventMemberDetails::HAS_NOT_ANSWERERD){
                        $friend = new Friend();
                        $friend->setId($event_member_detail->getInvitedFriendId());
                        if($friend->getFromDatabase()) {
                            PushNotification::delete_notification($event->getId(), PushNotification::NEW_EVENT_INVITATION, $friend);
                        }
                    }
                }
                else {
                    // l'utilisateur n'était pas invité
                    // on regarde si l'utilisateur a le droit d'inviter cet amo
                    $friend_link = new FriendLink();
                    $friend_link->setUserId($invited_friend_id);
                    $friend_link->setFriendId(Application::getUser()->getId());
                    if($friend_link->getFromDatabase()){
                        // l'utilisateur est bien ami avec cette personne
                        if($friend_link->getRelation()){
                            // l'ami le considère comme un bon ami
                            // il peut l'inviter
                            $friend = new Friend();
                            $friend->setId($invited_friend_id);
                            if($friend->getFromDatabase()){
                                // l'ami est bien dans la base de données
                                $event_member_detail->setFriendLatitude($friend->getLatitude());
                                $event_member_detail->setFriendLongitude($friend->getLongitude());
                                $event_member_detail->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
                                $event_member_detail->setCreator(EventMemberDetails::IS_NOT_CREATOR);
                                $event_member_detail->addToDataBase();
                                // on envoie une notification push à l'utilisateur
                                PushNotification::generate_new_invitation_push($friend, $event);
                            }
                        }
                    }
                }
            }
        }

    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }

}
<?php
namespace ZONNY\Controllers\Events;

use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventMemberDetails;
use ZONNY\Models\Account\Friend;
use ZONNY\Models\Account\FriendLink;
use ZONNY\Models\Push\PushNotification;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class CreateEvent implements \JsonSerializable
{

    /**
     * @SWG\Post(
     *     path="/event",
     *     summary="Create an event",
     *     tags={"event"},
     *     description="Allow the user to create an event with his friends.",
     *     operationId="createEvent",
     *     produces={"application/json"},
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
     *         required=true,
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
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Parameter(
     *         description="Datetime of the event's end. The datetime must be in the next 24h after the event's start.",
     *         in="formData",
     *         name="end_time",
     *         required=true,
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
     *         required=true,
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
     * Crée un évènement
     * CreateEvent constructor.
     * @param $name
     * @param $public
     * @param $latitude
     * @param $longitude
     * @param $start_time
     * @param $end_time
     * @param $information
     * @param $picture_url
     * @param $invited_friends_id
     * @throws PublicError
     * @throws \Exception
     */
    public function __construct($name, $public, $latitude, $longitude, $start_time, $end_time, $information, $picture_url, $invited_friends_id)
    {
        Application::check_variables($public, true, true, "^[0-1]$", "Invalid public parameter format.", ErrorCode::INVALID_PUBLIC_VARIABLE);
        Application::check_variables($start_time, true, true, "", "Start_time datetime invalid format.", ErrorCode::INVALID_DATETIME);
        Application::check_variables($end_time, true, true, "", "End_time datetime invalid format.", ErrorCode::INVALID_DATETIME);
        Application::check_variables($invited_friends_id, true, true, "^([0-9]+,?)+$", "Invited_friend_id variable must be a string separated by coma. Ex: 15,65,2.", ErrorCode::INVALID_FRIEND_ID);
        Application::check_variables($latitude, false, true, "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$", "Latitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        Application::check_variables($longitude, false, true, "^\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$", "Longitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        Application::check_variables($picture_url, false, true, "((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;\"':<]|\.\s|$)", "Invalid picture_url format.", ErrorCode::INVALID_URL);

        // on vérifie la cohérence des dates
        $current_datetime = new \DateTime();
        $start_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $start_time);
        $end_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $end_time);
        $diff = $start_time_datetime->getTimestamp() - $current_datetime->getTimestamp();
        if($diff>MAX_START_EVENT_INTERVAL || $diff<0){
            throw new PublicError("Event must start in the next 24h.", ErrorCode::EVENT_START_NEXT_24H);
        }
        $diff = $end_time_datetime->getTimestamp() - $start_time_datetime->getTimestamp();
        if($diff>MAX_EVENT_DURATION){
            throw new PublicError("Event duration can be only 24h max.", ErrorCode::EVENT_DURATION_24H_MAX);
        }
        if($diff<MIN_EVENT_DURATION){
            throw new PublicError("Event duration can be inferior to 1 minute.", ErrorCode::EVENT_DURATION_LESS_1MINUTE);
        }
        // on crée l'évènement
        $event = new Event();
        $event->setName($name);
        $event->setPublic($public);
        $event->setLatitude($latitude);
        $event->setLongitude($longitude);
        $event->setStartTime($start_time);
        $event->setEndTime($end_time);
        $event->setInformation($information);
        $event->setPictureUrl($picture_url);
        // on défini le créateur de l'évènement comme l'utilisateur actuel
        $event->setCreatorId(Application::getUser()->getId());
        $event->setCreatorLatitude(Application::getUser()->getLatitude());
        $event->setCreatorLongitude(Application::getUser()->getLongitude());
        // on défini si l'évènement a une position précise ou non
        if(empty($latitude) && empty($longitude)){
            $event->setWithLocalisation(0);
        }
        else {
            $event->setWithLocalisation(1);
        }
        // on ajoute à la base de données
        $event->addToDataBase();
        // si une erreur se produit, on doit la catcher pour supprimer l'évènement
        try {
            // on s'occupe des amis invités
            $total_latitude = 0;
            $total_longitude = 0;
            $nbr_invited_friends = 0;
            $invited_friends_id = Event::getArrayParticipantsFromString($invited_friends_id, Application::getUser());
            // pour tous les participants
            if (!empty($invited_friends_id)) {
                foreach ($invited_friends_id as $key => $invited_friend_id) {
                    // on crée vérifie si l'ami a donné l'autorisation à l'utilisateur
                    $friend_link = new FriendLink();
                    $friend_link->setUserId($invited_friend_id);
                    $friend_link->setFriendId(Application::getUser()->getId());
                    if ($friend_link->getFromDatabase()) {
                        if ($friend_link->getRelation()) {
                            // l'autorisation est donnée par l'ami
                            // on vérifie si l'ami n'est pas en mode fantôme
                            $friend = new Friend();
                            $friend->setId($invited_friend_id);
                            if ($friend->getFromDatabase()) {
                                // l'ami existe toujours dans la base de données
                                if (!$friend->isUnavailable()) {
                                    //l'ami n'est pas en mode fantôme
                                    $event_member = new EventMemberDetails();
                                    $event_member->setEventId($event->getId());
                                    $event_member->setInvitedFriendId($friend->getId());
                                    $event_member->setFriendLatitude($friend->getLatitude());
                                    $total_latitude += $friend->getLatitude();
                                    $event_member->setFriendLongitude($friend->getLongitude());
                                    $total_longitude += $friend->getLongitude();
                                    $event_member->setCreator(EventMemberDetails::IS_NOT_CREATOR);
                                    $event_member->setResponse(EventMemberDetails::HAS_NOT_ANSWERERD);
                                    $event_member->addToDataBase();
                                    $nbr_invited_friends++;
                                    // on envoie une notification push à l'invité
                                    PushNotification::generate_new_invitation_push($friend, $event);
                                }
                            }
                        }
                    }
                }
                // on ajoute maintenant l'utilisateur comme invité à l'évènement
                if ($nbr_invited_friends > 0) {
                    $event_member = new EventMemberDetails();
                    $event_member->setEventId($event->getId());
                    $event_member->setInvitedFriendId(Application::getUser()->getId());
                    $event_member->setFriendLatitude(Application::getUser()->getLatitude());
                    $total_latitude += Application::getUser()->getLatitude();
                    $event_member->setFriendLongitude(Application::getUser()->getLongitude());
                    $total_longitude += Application::getUser()->getLongitude();
                    $event_member->setCreator(EventMemberDetails::IS_CREATOR);
                    $event_member->setResponse(EventMemberDetails::HE_COMES);
                    $event_member->addToDataBase();
                    $nbr_invited_friends++;

                    // on met à jour la localisation de l'évènement s'il n'y avait pas d'adresse précise. Il s'agit alors du point central entre tous les invités
                    if (!$event->getWithLocalisation()) {
                        $event->setLatitude($total_latitude / $nbr_invited_friends);
                        $event->setLongitude($total_longitude / $nbr_invited_friends);
                        $event->updateToDataBase();
                    }
                } else {
                    // aucun n'ami n'a été invité finalement
                    // on supprime donc l'évènement
                    $event->deleteFromDataBase();
                    throw new PublicError("You can't invite these guests.", ErrorCode::EVENT_CREATION_WITHOUT_FRIEND);
                }
            } else {
                $event->deleteFromDataBase();
            }

        } catch (\Exception $e) {
            // si une erreur arrive, on doit supprimer les traces insérées dans la base de données
            $event_guests = $event->getAllGuests(true);
            // on supprime les possibles invités
            if(!empty($event_guests)){
                foreach ($event_guests as $event_guest){
                    /** @var  $event_member_details EventMemberDetails */
                    $event_member_details = (object)$event_guest['event_member_details'];
                    $event_member_details->deleteFromDataBase();
                }
            }
            $event->deleteFromDataBase();
            // on relance l'erreur
            throw $e;
        }
    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }

}
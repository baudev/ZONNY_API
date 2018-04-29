<?php

namespace ZONNY\Controllers\Friends;

use ZONNY\Models\Accounts\Friend;
use ZONNY\Models\Events\Event;
use ZONNY\Models\Accounts\FriendLink;
use ZONNY\Utils\Application;

class GetInvitableFriends implements \JsonSerializable
{

    private $_event_id;

    /**
     * @SWG\Get(
     *     path="/account/invitable_friends/{event_id}",
     *     summary="Get all user's friend invitable to the event",
     *     tags={"account"},
     *     description="Get all friends invitable to the event having the event_id.",
     *     operationId="getAllInvitableriends",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the concerning event.",
     *         in="path",
     *         name="event_id",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="object", ref="#/definitions/GoodFriend")
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
     * GetInvitableFriends constructor.
     * @param $_event_id
     */
    public function __construct($_event_id)
    {
        $this->_event_id = $_event_id;
    }

    /**
     * @return array
     * @throws \ZONNY\Utils\PublicError
     */
    public function jsonSerialize()
    {
        $response = array();
        // on récupère la liste de tous les amis de l'utilisateur
        $user_friends = Application::getUser()->getAllHisFriends(true);
        foreach ($user_friends as $user_friend){
            // on regarde les relations d'amitiés entre les utilisateurs
            // on converti le tableau en objects
            /** @var  $friend Friend */
            $friend = (object)$user_friend['friend'];
            // on récupère la relation définie par l'utilisateur
            /** @var  $user_link FriendLink */
            $user_link = (object)$user_friend['friend_link'];
            // on récupère la relation définie par l'ami
            $friend_link = new FriendLink();
            $friend_link->setUserId($friend->getId());
            $friend_link->setFriendId(Application::getUser()->getId());
            if($friend_link->getFromDatabase()){
                // on vérifie que les autorisations des deux côtés sont données et que l'ami n'est pas en mode fantôme
                if(!$friend->isUnavailable() && $user_link->getAuthorization() && $friend_link->getRelation()){
                    $response[] = $friend;
                }
            }
        }
        // on doit maintenant ajouter tous les invités à l'évènement obligatoirement pour qu'il puisse les retirer également de la liste des invités si besoin
        $event = new Event();
        $event->setId($this->getEventId());
        if($event->getFromDatabase()){
            $event_guests = $event->getAllGuests(false);
            foreach ($event_guests as $event_guest){
                /** @var  $friend Friend */
                $friend = (object)$event_guest['friend'];
                // on ne considère pas l'utilisateur
                if($friend->getId()!=Application::getUser()->getId()) {
                    // on vérifie si l'ami en cours n'existe pas déjà dans le tableau
                    $already_in_array = false;
                    if(!empty($response)) {
                        foreach ($response as $friend_in_response) {
                            /** @var  $friend_in_response Friend */
                            $friend_in_response = (object)$friend_in_response;
                            if ($friend_in_response->getId() == $friend->getId()) {
                                $already_in_array = true;
                            }
                        }
                        if (!$already_in_array) {
                            $response[] = $friend;
                        }
                    }
                    else {
                        $response[] = $friend;
                    }
                }
            }
        }
        return $response;
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

}
<?php
namespace ZONNY\Controllers\Map;

use ZONNY\Models\Account\Friend;
use ZONNY\Models\Account\FriendLink;
use ZONNY\Models\Events\Event;
use ZONNY\Utils\Application;

class ShowMap implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/",
     *     summary="Get all elements to show on the Map",
     *     tags={"map"},
     *     description="Return all elements (friends, events, places and so on) to display on the Map.",
     *     operationId="getMap",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="friends", type="array",
     *                  @SWG\Items(type="object", ref="#/definitions/EventMemberDetails")
     *              ),
     *             @SWG\Property(property="events", type="array",
     *                  @SWG\Items(type="object", properties={
     *                   @SWG\Property(property="event", type="object", ref="#/definitions/Event"),
     *                   @SWG\Property(property="event_member_details", type="object", ref="#/definitions/EventMemberDetails")
     *                  })
     *              ),
     *              @SWG\Property(property="public_places", type="array",
     *                  @SWG\Items(type="object", ref="#/definitions/PublicPlace")
     *              ),
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
     * @throws \ZONNY\Utils\PublicError
     */
    public function jsonSerialize()
    {
        $response = array();
        $array_friends = array();
        $array_events = array();
        $array_public_places = array();
        // on recupère tous les amis de l'utilisateur
        foreach (Application::getUser()->getAllHisFriends(true) as $friend_value){
            /** @var  $friend Friend */
            $friend = (object)$friend_value['friend'];
            /** @var  $friend_link_by_user FriendLink */
            $friend_link_by_user = (object)$friend_value['friend_link'];
            // on regarde la relation entre les deux utilisateurs par l'ami
            $friend_link_by_friend = new FriendLink();
            $friend_link_by_friend->setUserId($friend->getId());
            $friend_link_by_friend->setFriendId(Application::getUser()->getId());
            if($friend_link_by_friend->getFromDatabase()) {
                // les relations des deux côtés sont bonnes et l'ami n'est pas en mode fantôme
                if ($friend_link_by_user->getRelation() && $friend_link_by_friend->getRelation() && !$friend->isUnavailable() && $friend->is_location_valid()){
                    $array_friends[] = $friend;
                }
            }
        }
        // on recupère tous les évènements en cours
        $array_events = Event::getAllCurrentsEvents();
        $response["friends"] = $array_friends;
        $response["events"] = $array_events;
        return $response;
    }
}
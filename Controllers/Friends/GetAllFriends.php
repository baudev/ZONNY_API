<?php
namespace ZONNY\Controllers\Friends;

use ZONNY\Utils\Application;

class GetAllFriends implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/account/friends",
     *     summary="Get all user's friend",
     *     tags={"account"},
     *     description="Return all user's friend and the consideration of the user for each one.",
     *     operationId="getAllUserFriends",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="object", properties={
     *                   @SWG\Property(property="friend", type="object", ref="#/definitions/GoodFriend"),
     *                   @SWG\Property(property="friend_link", type="object", ref="#/definitions/FriendLink")
     *             })
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

    public function jsonSerialize()
    {
        return Application::getUser()->getAllHisFriends(true);
    }

}
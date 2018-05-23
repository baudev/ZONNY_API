<?php
namespace ZONNY\Controllers\Friends;

use ZONNY\Models\Account\Friend;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class GetFriend implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/account/friends/{friend_id}",
     *     summary="Get information about a friend",
     *     tags={"account"},
     *     description="Get all information concerning the friend. Depending of the relation between the user and the friend, all, partial or any information could be returned (Error in the last case).",
     *     operationId="getFriend",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the considered friend.",
     *         in="path",
     *         name="friend_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request. Could be a GoodFriend or NormalFriend model.",
     *         @SWG\Items(ref="#/definitions/GoodFriend"),
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

    private $_friend;

    /**
     * GetFriend constructor.
     * @param $friend_id
     * @throws \ZONNY\Utils\PublicError
     */
    public function __construct($friend_id)
    {
        Application::check_variables($friend_id, true, true, "^[0-9]+$", "Invalid friend_id format.", ErrorCode::INVALID_FRIEND_ID);
        $friend = new Friend();
        $friend->setId($friend_id);
        if(!$friend->getFromDatabase()){
            throw new PublicError("The friend's id given doesn't seem to be your friend.", ErrorCode::NOT_A_FRIEND);
        }
        $this->setFriend($friend);
    }

    public function jsonSerialize()
    {
        return $this->getFriend();
    }

    /**
     * @return mixed
     */
    public function getFriend():Friend
    {
        return $this->_friend;
    }

    /**
     * @param mixed $friend
     */
    public function setFriend(Friend $friend): void
    {
        $this->_friend = $friend;
    }


}
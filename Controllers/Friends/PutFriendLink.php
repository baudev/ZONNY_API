<?php
namespace ZONNY\Controllers\Friends;

use ZONNY\Models\Account\FriendLink;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class PutFriendLink implements \JsonSerializable
{

    /**
     * @SWG\Put(
     *     path="/account/friends",
     *     summary="Edit a friend relation",
     *     tags={"account"},
     *     description="Allow the user to consider a friend as a good one or not.",
     *     operationId="EditFriendRelation",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the considered friend.",
     *         in="formData",
     *         name="friend_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="1 if the friend is considered as a good friend. Otherwise, 0.",
     *         in="formData",
     *         name="authorization",
     *         required=true,
     *         type="integer",
     *         enum={0,1}
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
     * PutFriendLink constructor.
     * @param $friend_id
     * @param $authorization
     * @throws PublicError
     */
    public function __construct($friend_id, $authorization)
    {
        Application::check_variables($friend_id, true, true, "^[0-9]+$", "Invalid friend_id format.", ErrorCode::INVALID_FRIEND_ID);
        Application::check_variables($authorization, true, true, "^[0-1]$", "Invalid authorization parameter format.", ErrorCode::INVALID_AUTH_VARIABLE);
        $friend_relation = new FriendLink();
        $friend_relation->setUserId(Application::getUser()->getId());
        $friend_relation->setFriendId($friend_id);
        // on vérifie si la relation existe bien dans la base de données
        if($friend_relation->getFromDatabase()){
            // on met à jour la relation
            $friend_relation->setRelation($authorization);
            $friend_relation->updateToDataBase();
        }
        else {
            throw new PublicError("The friend's id given doesn't seem to be your friend.", ErrorCode::NOT_A_FRIEND);
        }
    }


    public function jsonSerialize()
    {
        return array("response" => "ok");
    }

}
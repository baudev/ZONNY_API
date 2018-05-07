<?php

namespace ZONNY\Controllers\Accounts;


use ZONNY\Models\Accounts\FacebookUser;
use ZONNY\Models\Accounts\User;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PrivateError;
use ZONNY\Utils\PublicError;

class CreateEditAccount implements \JsonSerializable
{

    private $_user;

    /**
     * @SWG\Post(
     *     path="/account",
     *     summary="Create user account",
     *     tags={"account"},
     *     description="Allow the user to create an account from his Facebook one.",
     *     operationId="createAccountToken",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="User Facebook token",
     *         in="formData",
     *         name="fb_access_token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="User Facebook token expiration datetime",
     *         in="formData",
     *         name="expiration_datetime_token",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="user", type="object", ref="#/definitions/User")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="error during request",
     *         @SWG\Items(ref="#/definitions/Error"),
     *     )
     * )
     */

    /**
     * @SWG\Put(
     *     path="/account",
     *     summary="Update Facebook user account",
     *     tags={"account"},
     *     description="Allow the user to update his Facebook credentials.",
     *     operationId="updateAccountToken",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="User Facebook token",
     *         in="formData",
     *         name="fb_access_token",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="User Facebook token expiration datetime",
     *         in="formData",
     *         name="expiration_datetime_token",
     *         required=true,
     *         type="string",
     *         format="datetime"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="user", type="object", ref="#/definitions/User")
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
     * PostPutAccount constructor.
     * @param $fb_access_token
     * @param $expire
     * @throws PublicError
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function __construct($fb_access_token, $expire)
    {
        Application::check_variables($fb_access_token, true, true, "^[a-z0-9A-Z]+$", "Facebook token has not valid format", ErrorCode::INVALID_FB_ACCESS_TOKEN);
        Application::check_variables($expire, true, true, "^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$", "Facebook token's expiration date has not valid format", ErrorCode::INVALID_FB_ACCESS_TOKEN_EXPIRATION);
        $user = new User();
        $user->setFbAccessToken($fb_access_token);
        $user->setExpire($expire);
        $this->setUser($user);
        // on recupère les informations concernant l'utilisateur depuis Facebook (on passe la référence pour modifier directement l'objet depuis la fonction)
        $this->connectionFacebook($user);
        // on insère ou met à jour la base de données avec les informations de l'utilisateur
        $this->UserDatabase();
        // on récupère la liste des amis Facebook utilisant l'application
        $this->getFacebookFriends($user);
    }

    public function jsonSerialize()
    {
        return array("user" => $this->getUser());
    }

    /**
     * @param User $user
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @throws PublicError
     */
    private function connectionFacebook(User &$user){
        // on initialise l'API Facebook
        FacebookUser::init();
        // on récupère l'information sur l'utilisateur depuis Facebook
        $facebook = new FacebookUser();
        $facebook->getUserInfos($user);
    }

    /**
     * @param User $user
     * @throws PublicError
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    private function getFacebookFriends(User &$user){
        $facebook = new FacebookUser();
        $facebook->getUserFriends($user);
    }

    /**
     * @return mixed
     * @throws PublicError
     */
    private function UserDatabase(){
        if(!empty($this->getUser()->getFbAccessToken()) && !empty($this->getUser()->getExpire()) && !empty($this->getUser()->getFbUserId())){
            // toutes les informations nécessaires sont présentes
            // on tente de récupérer l'utilisateur avec le même fb_user_id
            if($this->getUser()->getFromDatabase()){
                // l'utilisateur existe deja
                // on met la base de données à jour
                $this->getUser()->updateToDataBase();
            }
            else {
                // l'utilisateur n'existe pas encore
                // on ajoute donc l'utilisateur à la base de données
                $this->getUser()->addToDataBase();
            }
        }
        else {
            // on insère l'erreur dans la table pour vérification ultérieure par l'équipe
            $private_error = new PrivateError("Missing values from Facebook Services.", ErrorCode::MISSING_PARAMTERS);
            $private_error->log_error($this->getUser());
            // on retourne une erreur
            throw new PublicError("Missing values from Facebook Services.", ErrorCode::MISSING_PARAMTERS);
        }
    }


    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->_user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->_user = $user;
    }

}
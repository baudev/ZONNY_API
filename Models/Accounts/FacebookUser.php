<?php

namespace ZONNY\Models\Accounts;


use Facebook\Facebook;
use ZONNY\Models\Push\PushNotification;

class FacebookUser
{

    private static $fb;

    /**
     * On initialise l'API de Facebook
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public static function init(){

        self::setFb(new Facebook([
            'app_id'                => FB_APP_ID,
            'app_secret'            => FB_APP_SECRET,
            'default_graph_version' => 'v2.12',
        ]));

    }

    /**
     * @param User $user
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @throws \ZONNY\Utils\PublicError
     */
    public function getUserInfos(User &$user){
        $infoperso = self::getFb()->get('me?fields=id,name,first_name,picture.width(500).height(500){is_silhouette,url},last_name', $user->getFbAccessToken());
        $response = $infoperso->getDecodedBody();
        // on défini les informations obtenus sur l'utilisateur depuis Facebook
        $user->setFbUserId($response['id']);
        $user->setName($response['name']);
        $user->setFirstName($response['first_name']);
        $user->setLastName($response['last_name']);
        $user->setProfilePictureUrl(($response['picture']['data']['is_silhouette'] == false) ? $response['picture']['data']['url'] : null);
    }


    /**
     * @param User $user
     * @throws \Facebook\Exceptions\FacebookSDKException
     * @throws \ZONNY\Utils\PublicError
     */
    public function getUserFriends(User $user){
        $response = self::getFb()->get('me/friends?fields=name,first_name,last_name,devices,installed,context,id&limit=50000', $user->getFbAccessToken());
        $response = $response->getDecodedBody();
        foreach ($response['data'] as $rep) {
            // on crée la relation entre les utilisateurs dans les deux sens
            $friend_link = new FriendLink();
            $friend = new Friend();
            // on récupère l'identifiant de l'ami à partir de son fb_user_id
            // on vérifie que l'ami existe bien dans la base de données
            $friend->setFbUserId($rep['installed']);
            if($friend->getFromDatabase()) {
                $friend_link->setFriendId($friend->getId());
                $friend_link->setId($user->getId());
                $friend_link->setMutualFriends($rep['context']['mutual_friends']['summary']['total_count']);
                $friend_link->setMutualLikes($rep['context']['mutual_likes']['summary']['total_count']);
                $friend_link->setRelation(1);
                $friend_link->addToDataBase();
                // on crée la relation dans le sens inverse
                $inverse_friend_link = clone($friend_link);
                $inverse_friend_link->setFriendId($user->getId());
                $inverse_friend_link->setUserId($friend->getId());
                $inverse_friend_link->addToDataBase();
                // s'il s'agit d'un bon ami alors on le notifie de l'arrivée de l'utilisateur
                if ($friend_link->getMutualFriends() > MIN_FACEBOOK_COMMUN_FRIENDS || $friend_link->getMutualLikes() > MIN_FACEBOOK_COMMUN_LIKES) {
                    PushNotification::generate_new_friend_push($friend);
                }
            }
        }
    }

    /**
     * @return Facebook
     */
    public static function getFb(): Facebook
    {
        return self::$fb;
    }

    /**
     * @param Facebook $fb
     */
    public static function setFb(Facebook $fb): void
    {
        self::$fb = $fb;
    }




}
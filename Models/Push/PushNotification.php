<?php

namespace ZONNY\Models\Push;


use ZONNY\Models\Accounts\Friend;
use ZONNY\Models\Accounts\FriendLink;
use ZONNY\Models\Events\Event;
use ZONNY\Models\Events\EventRequest;
use ZONNY\Utils\Application;

class PushNotification
{

    public const NEW_FRIEND = "new_friend";
    public const NEW_EVENT_INVITATION = "new_invitation";
    public const NEW_EVENT_REQUEST = "event_request";
    private const DELETE_NOTIFICATION = "delete_notification";

    /**
     *  Génère une notification indicant qu'un nouvel bon ami vient d'arriver sur l'application
     * @param Friend $friend
     * @return bool            si la notification a bien été envoyée ou non
     * @throws \ZONNY\Utils\PublicError
     */
    public static function generate_new_friend_push(Friend $friend): bool
    {
        // on vérifie que le gcm_id n'est pas null
        if(!empty($friend->getGcmRegistrationId())) {
            // on vérifie que l'ami considère l'utilisateur comme un bon ami
            $friend_relation = new FriendLink();
            $friend_relation->setUserId($friend->getId());
            $friend_relation->setFriendId(Application::getUser()->getId());
            if ($friend_relation->getRelation()) {
                // on peut notifier l'ami
                $sub_information['first_name'] = Application::getUser()->getFirstName() ?? Application::getUser()->getName();
                $sub_information['profile_picture_url'] = Application::getUser()->getProfilePictureUrl();
                // on met en forme les données
                $push_data = self::generate_data(PushNotification::NEW_FRIEND, $sub_information);
                $gcm_registration_ids[] = $friend->getGcmRegistrationId();
                // on met en forme les données avec les gcm_id
                $params = self::generate_format($gcm_registration_ids, $push_data);
                // on envoie la notification
                $result = self::send($params);
                $data_result = json_decode($result, true);
                if ($data_result['success'] == 1) {
                    return true;
                } else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * Génère une notification quand un ami vient d'être invité par l'utilisateur à un évènement
     * @param Friend $friend
     * @param Event $event
     * @return bool [type]            [description]
     * @throws \ZONNY\Utils\PublicError
     */
    public static function generate_new_invitation_push(Friend $friend, Event $event): bool
    {
        // on vérifie que le gcm_id n'est pas null
        if(!empty($friend->getGcmRegistrationId())) {
            // on vérifie que l'ami considère l'utilisateur comme un bon ami
            $friend_relation = new FriendLink();
            $friend_relation->setUserId($friend->getId());
            $friend_relation->setFriendId(Application::getUser()->getId());
            if ($friend_relation->getRelation()) {
                $sub_information['first_name'] = Application::getUser()->getFirstName() ?? Application::getUser()->getName();
                $sub_information['profile_picture_url'] = Application::getUser()->getProfilePictureUrl();
                $sub_information['event_id'] = $event->getId();
                $sub_information['event_name'] = $event->getName();
                $sub_information['event_picture'] = $event->getPictureUrl();
                // we remove two to not considerer the creator and the friend
                $sub_information['number_guests'] = $event->getNumberGuests()-2;
                // on formate les données
                $push_data = self::generate_data(PushNotification::NEW_EVENT_INVITATION, $sub_information);
                $gcm_registration_ids[] = $friend->getGcmRegistrationId();
                // on met en forme les données avec les gcm_id
                $params = self::generate_format($gcm_registration_ids, $push_data);
                // on envoie la notification
                $result = self::send($params);
                $data_result = json_decode($result, true);
                if ($data_result['success'] == 1) {
                    return true;
                } else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * @param Friend $friend
     * @param Event $event
     * @param EventRequest $event_request
     * @return bool
     * @throws \ZONNY\Utils\PublicError
     */
    public static function generate_new_request_push(Friend $friend, Event $event, EventRequest $event_request)
    {
        // on vérifie que le gcm_id n'est pas null
        if(!empty($friend->getGcmRegistrationId())) {
            // on vérifie que l'ami considère l'utilisateur comme un bon ami
            $friend_relation = new FriendLink();
            $friend_relation->setUserId($friend->getId());
            $friend_relation->setFriendId(Application::getUser()->getId());
            if ($friend_relation->getRelation()) {
                // on peut notifier l'ami
                $sub_information['event_id'] = $event->getId();
                $sub_information['event_name'] = $event->getName();
                $sub_information['request_id'] = $event_request->getId();
                $sub_information['first_name'] = Application::getUser()->getFirstName() ?? Application::getUser()->getName();
                $sub_information['friend_id'] = Application::getUser()->getId();
                $sub_information['profile_picture_url'] = Application::getUser()->getProfilePictureUrl();
                // on formate les données
                $push_data = self::generate_data(PushNotification::NEW_EVENT_REQUEST, $sub_information);
                $gcm_registration_ids[] = $friend->getGcmRegistrationId();
                // on met en forme les données avec les gcm_id
                $params = self::generate_format($gcm_registration_ids, $push_data);
                // on envoie la notification maintenant
                $result      = self::send($params);
                $data_result = json_decode($result, true);
                if ($data_result['success'] == 1) {
                    return true;
                } else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * Génère une requête pour signaler de supprimer la notification Push chez l'utilisateur avec un id spécifique
     * @param  int $id_notification identifiant de la notification Push
     * @param  String $tag tag de la notification
     * @param Friend $friend
     * @return bool                  si la requête a bien été envoyée ou non
     * @throws \ZONNY\Utils\PublicError
     */
    public static function delete_notification(int $id_notification, string $tag, Friend $friend)
    {
        // on vérifie que le gcm_id n'est pas null
        if(!empty($friend->getGcmRegistrationId())) {
            // on vérifie que l'ami considère l'utilisateur comme un bon ami
            $friend_relation = new FriendLink();
            $friend_relation->setUserId($friend->getId());
            $friend_relation->setFriendId(Application::getUser()->getId());
            if ($friend_relation->getRelation()) {
                // on peut notifier l'ami
                $sub_information['notification_id'] = $id_notification;
                $sub_information['TAG'] = $tag;
                // on met en forme les données
                $push_data = self::generate_data(PushNotification::DELETE_NOTIFICATION, $sub_information);
                $gcm_registration_ids[] = $friend->getGcmRegistrationId();
                // on met en forme les données avec les gcm_id
                $params = self::generate_format($gcm_registration_ids, $push_data);
                // on envoie la notification
                $result = self::send($params);
                $data_result = json_decode($result, true);
                if ($data_result['success'] == 1) {
                    return true;
                } else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * Génère une notification Push avec le bon standard pour l'application
     *
     * @param String $category new_friend/new_invitation/new_message
     * @param $sub_information
     * @return array
     */
    private static function generate_data(String $category, $sub_information): array
    {
        // pour une gestion future des suppressions de notifications$
        // faire attention à ce que l'id n'interfère pas avec ceux des identifiants des évènements qui sont les id des notifications
        $_message['id']              = mt_rand(100000000, 999999999);
        $_message['category']       = $category;
        $_message['sub_information'] = $sub_information;
        return $_message;
    }

    /**
     * Génère la variable à envoyer par CURL à Google
     *
     * @param array $registration_ids
     * @param array $message
     * @return array
     */
    private static function generate_format(array $registration_ids, array $message): array
    {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data'             => $message,
        );
        return $fields;
    }

    /**
     * Envoie la notification
     * @param  array  $champs informations concernant la notification
     * @return string         réponse de Google concernant la notification envoyée
     */
    public static function send(array $champs): string
    {
        $url     = 'https://gcm-http.googleapis.com/gcm/send';
        $headers = array(
            'Authorization: key=' . FIREBASE_KEY,
            'Content-Type: application/json',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($champs));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

}
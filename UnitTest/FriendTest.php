<?php
namespace ZONNY\UnitTest;

use ZONNY\Models\Accounts\Friend;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Accounts\FriendLink;
use ZONNY\Models\Accounts\User;
use ZONNY\Utils\Application;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
Application::init();

class FriendTest extends TestCase
{

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testJsonSerialize()
    {
        // on crée deux utilisateurs
        $user_1 = new User(null);
        $user_1->setFbAccessToken("TEST");
        $user_1->setFbUserId("UNIT");
        $user_1->setExpire("2020-02-01 00:00:00");
        $user_1->setName("PRENOM NOM");
        $user_1->setFirstName("PRENOM");
        $user_1->setLastName("NOM");
        $user_1->setProfilePictureUrl("https://google.fr");
        $user_1->setLatitude(48.85);
        $user_1->setLongitude(2.28);
        $user_1->setLastLatitude(48.859);
        $user_1->setLastLongitude(2.28559);
        $user_1->setUnavailable("2040-03-01 12:12:12");
        $user_1->setGcmRegistrationId("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9lx");
        $user_1->setLocationLastCheckUp("2010-05-03 10:10:10");
        $user_1->setLastAddEvents("2011-03-05 06:00:00");
        $user_1->setLastAddEventsGoogle("2013-05-01 06:05:00");
        // on l'ajoute à la base de données
        $user_1->addToDataBase();
        $user_1->getFromDatabase();

        // on le défini comme utilisateur
        Application::setUser($user_1);

        $user_2 = new User(null);
        $user_2->setFbAccessToken("TEST2");
        $user_2->setFbUserId("UNIT2");
        $user_2->setExpire("2020-02-01 00:00:02");
        $user_2->setName("PRENOM NOM2");
        $user_2->setFirstName("PRENOM2");
        $user_2->setLastName("NOM2");
        $user_2->setProfilePictureUrl("https://google2.fr");
        $user_2->setLatitude(48.82);
        $user_2->setLongitude(2.22);
        $user_2->setLastLatitude(48.852);
        $user_2->setLastLongitude(2.28552);
        $user_2->setUnavailable("2040-03-01 12:12:13");
        $user_2->setGcmRegistrationId("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9l2");
        $user_2->setLocationLastCheckUp("2010-05-03 10:10:12");
        $user_2->setLastAddEvents("2011-03-05 06:00:02");
        $user_2->setLastAddEventsGoogle("2013-05-01 06:05:02");
        // on l'ajoute à la base de données
        $user_2->addToDataBase();
        $user_2->getFromDatabase();

        // on crée deux relations d'amitiés
        $user_1_link = new FriendLink();
        $user_1_link->setUserId($user_1->getId());
        $user_1_link->setFriendId($user_2->getId());
        $user_1_link->setMutualFriends(1);
        $user_1_link->setMutualLikes(0);
        $user_1_link->setRelation(FriendLink::GOOD_FRIEND);
        $user_1_link->addToDataBase();
        $user_1_link->getFromDatabase();

        $user_2_link = new FriendLink();
        $user_2_link->setUserId($user_2->getId());
        $user_2_link->setFriendId($user_1->getId());
        $user_2_link->setMutualFriends(1);
        $user_2_link->setMutualLikes(0);
        $user_2_link->setRelation(FriendLink::GOOD_FRIEND);
        $user_2_link->addToDataBase();
        $user_2_link->getFromDatabase();

        $friend_2 = new Friend();
        $friend_2->setId($user_2->getId());
        $friend_2->getFromDatabase();
        // on récupère le résultat de la fonction responsable de l'affichage en json
        $array_response = $friend_2->jsonSerialize();

        // sachant qu'il s'agit d'un bon ami, on doit récupérer toutes les informations sur l'ami
        $this->assertEquals(9, count($array_response));

        // on modifie la relation définie par l'ami
        $user_2_link->setRelation(FriendLink::NOT_GOOD_FRIEND);
        $user_2_link->updateToDataBase();
        $array_response = $friend_2->jsonSerialize();
        // sachant qu'il s'agit d'un ami correct, on doit récupérer seulement une partie des informations sur l'ami
        $this->assertEquals(3, count($array_response));

        // on modifie l'autre relation, celle définie par l'utilisateur
        $user_1_link->setRelation(FriendLink::NOT_GOOD_FRIEND);
        $user_1_link->updateToDataBase();
        // on remet celle de l'ami comme précédemment
        $user_2_link->setRelation(FriendLink::GOOD_FRIEND);
        $user_2_link->updateToDataBase();

        $array_response = $friend_2->jsonSerialize();
        // sachant qu'il s'agit d'un ami correct, on doit récupérer seulement une partie des informations sur l'ami
        $this->assertEquals(3, count($array_response));

        $user_1->deleteFromDataBase();
        $user_2->deleteFromDataBase();
        $user_1_link->deleteFromDataBase();
        $user_2_link->deleteFromDataBase();
    }
}

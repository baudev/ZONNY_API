<?php
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Accounts\User;
use ZONNY\Utils\DatetimeISO8601;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class UserTest extends TestCase
{

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testDeleteFromDataBase()
    {
        \ZONNY\Utils\Application::init();
        // on crée un utilisatateur
        $user = new User(null);
        $user->setName("TEST NAME");
        $user->setFbAccessToken("a");
        $user->setFbUserId("a");
        $user->setExpire("2020-02-01 00:00:00");
        $user->setProfilePictureUrl("https://google.fr");
        // on l'ajoute à la base de données
        $user->addToDataBase();
        // on le supprime
        $user->deleteFromDataBase();
        // on le recupère depuis la base de données
        // si false alors c'est que aucun utilisateur n'a été trouvé dans bdd
        $this->assertFalse($user->getFromDatabase($user->getId()));
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetFromDatabase()
    {
        // on crée un utilisatateur
        $user = new User(null);
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("UNIT");
        $user->setExpire("2020-02-01 00:00:00");
        $user->setName("PRENOM NOM");
        $user->setFirstName("PRENOM");
        $user->setLastName("NOM");
        $user->setProfilePictureUrl("https://google.fr");
        $user->setLatitude(48.85);
        $user->setLongitude(2.28);
        $user->setLastLatitude(48.859);
        $user->setLastLongitude(2.28559);
        $user->setUnavailable("2040-03-01 12:12:12");
        $user->setGcmRegistrationId("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9lx");
        $user->setLocationLastCheckUp("2010-05-03 10:10:10");
        $user->setLastAddEvents("2011-03-05 06:00:00");
        $user->setLastAddEventsGoogle("2013-05-01 06:05:00");
        // on l'ajoute à la base de données
        $user->addToDataBase();
        // on recupère l'identifiant
        $new_user = new User();
        $new_user->setId($user->getId());
        // on le recupère depuis la base de données
        $new_user->getFromDatabase();
        // on supprime de la base de données
        $new_user->deleteFromDataBase();
        // on vérifie que les valeurs définies précédemment sont bien les mêmes
        $this->assertRegExp('#\w{100}#', $new_user->getKeyApp());
        $this->assertEquals("TEST", $new_user->getFbAccessToken());
        $this->assertEquals("UNIT", $new_user->getFbUserId());
        $this->assertEquals(new DatetimeISO8601("2020-02-01 00:00:00+01:00"), $new_user->getExpire());
        $this->assertEquals("PRENOM NOM", $new_user->getName());
        $this->assertEquals("PRENOM", $new_user->getFirstName());
        $this->assertEquals("NOM", $new_user->getLastName());
        $this->assertEquals("https://google.fr", $new_user->getProfilePictureUrl());
        // regex car les latitudes et longitudes sont aléatorisés
        $this->assertRegExp('#48\.85#', number_format($new_user->getLatitude(),2,'.',''));
        $this->assertRegExp('#2\.2#', number_format($new_user->getLongitude(),2,'.',''));
        $this->assertEquals(48.859, $new_user->getLastLatitude());
        $this->assertEquals(2.28559, $new_user->getLastLongitude());
        $this->assertEquals(new DatetimeISO8601("2040-03-01 12:12:12"), $new_user->getUnavailable());
        $this->assertEquals("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9lx", $new_user->getGcmRegistrationId());
        $this->assertEquals(new DatetimeISO8601("2010-05-03 10:10:10"), $new_user->getLocationLastCheckUp());
        $this->assertEquals(new DatetimeISO8601("2011-03-05 06:00:00"), $new_user->getLastAddEvents());
        $this->assertEquals(new DatetimeISO8601("2013-05-01 06:05:00"), $new_user->getLastAddEventsGoogle());

    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testUpdateToDataBase()
    {
        \ZONNY\Utils\Application::init();
        // on crée un utilisatateur
        $user = new User(null);
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("UNIT");
        $user->setExpire("2020-02-01 00:00:00");
        $user->setName("PRENOM NOM");
        $user->setFirstName("PRENOM");
        $user->setLastName("NOM");
        $user->setProfilePictureUrl("https://google.fr");
        $user->setLatitude(48.85);
        $user->setLongitude(2.28);
        $user->setLastLatitude(48.859);
        $user->setLastLongitude(2.28559);
        $user->setUnavailable("2040-03-01 12:12:12");
        $user->setGcmRegistrationId("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9lx");
        $user->setLocationLastCheckUp("2010-05-03 10:10:10");
        $user->setLastAddEvents("2011-03-05 06:00:00");
        $user->setLastAddEventsGoogle("2013-05-01 06:05:00");
        // on l'ajoute à la base de données
        $user->addToDataBase();
        // on modifie l'utilisateur
        $user->setFbAccessToken("TEST2");
        $user->setFbUserId("UNIT2");
        $user->setExpire("2020-02-01 00:00:02");
        $user->setName("PRENOM NOM2");
        $user->setFirstName("PRENOM2");
        $user->setLastName("NOM2");
        $user->setProfilePictureUrl("https://google2.fr");
        $user->setLatitude(48.82);
        $user->setLongitude(2.22);
        $user->setLastLatitude(48.852);
        $user->setLastLongitude(2.28552);
        $user->setUnavailable("2040-03-01 12:12:13");
        $user->setGcmRegistrationId("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9l2");
        $user->setLocationLastCheckUp("2010-05-03 10:10:12");
        $user->setLastAddEvents("2011-03-05 06:00:02");
        $user->setLastAddEventsGoogle("2013-05-01 06:05:02");
        // on met à jour
        $user->updateToDataBase();
        // on le recupère depuis la base de données
        $user->getFromDatabase();
        // on supprime de la base de données
        $user->deleteFromDataBase();
        // on vérifie que les valeurs définies précédemment sont bien les mêmes
        $this->assertRegExp('#\w{100}#', $user->getKeyApp());
        $this->assertEquals("TEST2", $user->getFbAccessToken());
        $this->assertEquals("UNIT2", $user->getFbUserId());
        $this->assertEquals(new DatetimeISO8601("2020-02-01 00:00:02+01:00"), $user->getExpire());
        $this->assertEquals("PRENOM NOM2", $user->getName());
        $this->assertEquals("PRENOM2", $user->getFirstName());
        $this->assertEquals("NOM2", $user->getLastName());
        $this->assertEquals("https://google2.fr", $user->getProfilePictureUrl());
        // regex car les latitudes et longitudes sont aléatorisés
        $this->assertRegExp('#48\.82#', number_format($user->getLatitude(),2,'.',''));
        $this->assertRegExp('#2\.22#', number_format($user->getLongitude(),2,'.',''));
        $this->assertEquals(48.852, $user->getLastLatitude());
        $this->assertEquals(2.28552, $user->getLastLongitude());
        echo $user->getUnavailable();
        $this->assertEquals(new DatetimeISO8601("2040-03-01 12:12:13"), $user->getUnavailable());
        $this->assertEquals("cG0vXW_JWZ8:APA91bFV58NILulQFs_mJLkQMKpdfS_hs5GhASDGqApyYi86KB4BZIUziXYcBJ_q1qcJeuBEoJzDfz5AmpZgDssp0D4kc-o5gbJe-tYhDHbT_fbBr1uHkFIM8-rxrkfzqXosQc0ox9l2", $user->getGcmRegistrationId());
        $this->assertEquals(new DatetimeISO8601("2010-05-03 10:10:12"), $user->getLocationLastCheckUp());
        $this->assertEquals(new DatetimeISO8601("2011-03-05 06:00:02"), $user->getLastAddEvents());
        $this->assertEquals(new DatetimeISO8601("2013-05-01 06:05:02"), $user->getLastAddEventsGoogle());
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testAddToDataBase()
    {
        \ZONNY\Utils\Application::init();
        // on crée un utilisatateur
        $user = new User(null);
        $user->setName("TEST NAME");
        $user->setFbAccessToken("TEST");
        $user->setFbUserId("UNITAIRE");
        $user->setExpire("2020-02-01 00:00:00");
        $user->setProfilePictureUrl("https://google.fr");
        // on l'ajoute à la base de données
        $user->addToDataBase();
        // on le recupère depuis la base de données
        $user->getFromDatabase($user->getId());
        // on vérifie que l'identifiant est bien non null
        $this->assertNotNull($user->getId());
        // on supprime de la base de données;
        $user->deleteFromDataBase();
    }
}
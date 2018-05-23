<?php

namespace ZONNY\UnitTest;

use ZONNY\Models\Account\FriendLink;
use PHPUnit\Framework\TestCase;
use ZONNY\Models\Account\User;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../config.php';
\ZONNY\Utils\Application::init();

class FriendLinkTest extends TestCase
{

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testDeleteFromDataBase()
    {
        \ZONNY\Utils\Application::init();
        // on crée la relation
        $friend = new User();
        $friend->setId(3);
        $user = new User();
        $user->setId(4);
        $friend_links = new FriendLink(null);
        $friend_links->setFriendId($friend->getId());
        $friend_links->setUserId($user->getId());
        $friend_links->setMutualFriends(3);
        $friend_links->setMutualLikes(60);
        $friend_links->setRelation(1);
        // on l'ajoute à la base de données
        $friend_links->addToDataBase();
        // on le supprime
        $friend_links->deleteFromDataBase();
        // on le recupère depuis la base de données
        // retourne false si rien trouvé dans la bdd
        $this->assertFalse($friend_links->getFromDatabase());
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testAddFromDataBase()
    {
        \ZONNY\Utils\Application::init();
        // on crée la relation
        $friend = new User();
        $friend->setId(3);
        $user = new User();
        $user->setId(4);
        $friend_links = new FriendLink(null);
        $friend_links->setFriendId($friend->getId());
        $friend_links->setUserId($user->getId());
        $friend_links->setMutualFriends(3);
        $friend_links->setMutualLikes(60);
        $friend_links->setRelation(1);
        // on l'ajoute à la base de données
        $friend_links->addToDataBase();
        // on recupère depuis la base de données
        $friend_links->getFromDatabase();
        // on le supprime
        $friend_links->deleteFromDataBase();
        // on vérifie que les valeurs définies précédemment sont bien les mêmes
        $this->assertRegExp('#\d+#', $friend_links->getId());
        $this->assertEquals(3, $friend_links->getFriendId());
        $this->assertEquals(4, $friend_links->getUserId());
        $this->assertEquals(3, $friend_links->getMutualFriends());
        $this->assertEquals(60, $friend_links->getMutualLikes());
        $this->assertEquals(1, $friend_links->getRelation());
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testGetFromDatabase()
    {
        \ZONNY\Utils\Application::init();
        // on crée la relation
        $friend = new User();
        $friend->setId(3);
        $user = new User();
        $user->setId(4);
        $friend_links = new FriendLink(null);
        $friend_links->setFriendId($friend->getId());
        $friend_links->setUserId($user->getId());
        $friend_links->setMutualFriends(3);
        $friend_links->setMutualLikes(60);
        $friend_links->setRelation(1);
        // on l'ajoute à la base de données
        $friend_links->addToDataBase();
        // on crée une autre instance de l'objet
        $new_friend_links = new FriendLink();
        $new_friend_links->setId($friend_links->getId());
        // on recupère depuis la base de données
        $new_friend_links->getFromDatabase();
        // on le supprime
        $new_friend_links->deleteFromDataBase();
        // on vérifie que les valeurs définies précédemment sont bien les mêmes
        $this->assertRegExp('#\d+#', $new_friend_links->getId());
        $this->assertEquals(3, $new_friend_links->getFriendId());
        $this->assertEquals(4, $new_friend_links->getUserId());
        $this->assertEquals(3, $new_friend_links->getMutualFriends());
        $this->assertEquals(60, $new_friend_links->getMutualLikes());
        $this->assertEquals(1, $new_friend_links->getRelation());
    }

    /**
     * @throws \ZONNY\Utils\PublicError
     */
    public function testUpdateToDataBase()
    {
        \ZONNY\Utils\Application::init();
        // on crée la relation
        $friend = new User();
        $friend->setId(5);
        $user = new User();
        $user->setId(6);
        $friend_links = new FriendLink(null);
        $friend_links->setFriendId($friend->getId());
        $friend_links->setUserId($user->getId());
        $friend_links->setMutualFriends(18);
        $friend_links->setMutualLikes(65);
        $friend_links->setRelation(1);
        // on l'ajoute à la base de données
        $friend_links->addToDataBase();
        // on met à jours les données
        $friend->setId(3);
        $user->setId(4);
        $friend_links->setFriendId($friend->getId());
        $friend_links->setUserId($user->getId());
        $friend_links->setMutualFriends(3);
        $friend_links->setMutualLikes(60);
        $friend_links->setRelation(1);
        // on update la base de données
        $friend_links->updateToDataBase();
        // on le supprime
        $friend_links->deleteFromDataBase();
        // on vérifie que les valeurs définies précédemment sont bien les mêmes
        $this->assertRegExp('#\d+#', $friend_links->getId());
        $this->assertEquals(3, $friend_links->getFriendId());
        $this->assertEquals(4, $friend_links->getUserId());
        $this->assertEquals(3, $friend_links->getMutualFriends());
        $this->assertEquals(60, $friend_links->getMutualLikes());
        $this->assertEquals(1, $friend_links->getRelation());
    }
}

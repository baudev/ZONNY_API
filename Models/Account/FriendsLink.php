<?php

namespace ZONNY\Models\Account;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class FriendsLinks
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="friends_links")
 */
class FriendsLink
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */private $id;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friends_links")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friends_links")
     */
    private $friend;
    /**
     * @ORM\Column(type="integer", nullable=true, name="facebook_mutual_friends")
     */
    private $facebookMutualFriends;
    /**
     * @ORM\Column(type="integer", nullable=true, name="facebook_mutual_likes")
     */
    private $facebookMutualLikes;
    /**
     * @ORM\Column(type="boolean", name="authorization")
     */
    private $authorization = false;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * @param mixed $friend
     */
    public function setFriend($friend): void
    {
        $this->friend = $friend;
    }

    /**
     * @return mixed
     */
    public function getFacebookMutualFriends()
    {
        return $this->facebookMutualFriends;
    }

    /**
     * @param mixed $facebookMutualFriends
     */
    public function setFacebookMutualFriends($facebookMutualFriends): void
    {
        $this->facebookMutualFriends = $facebookMutualFriends;
    }

    /**
     * @return mixed
     */
    public function getFacebookMutualLikes()
    {
        return $this->facebookMutualLikes;
    }

    /**
     * @param mixed $facebookMutualLikes
     */
    public function setFacebookMutualLikes($facebookMutualLikes): void
    {
        $this->facebookMutualLikes = $facebookMutualLikes;
    }

    /**
     * @return mixed
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param mixed $authorization
     */
    public function setAuthorization($authorization): void
    {
        $this->authorization = $authorization;
    }

    /**
     * @return mixed
     */
    public function getCreationDatetime()
    {
        return $this->creationDatetime;
    }

    /**
     * @param mixed $creationDatetime
     */
    public function setCreationDatetime($creationDatetime): void
    {
        $this->creationDatetime = $creationDatetime;
    }



}
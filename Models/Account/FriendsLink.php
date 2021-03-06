<?php

namespace ZONNY\Models\Account;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class FriendsLinks
 * @package ZONNY\Models\Accounts
 * @ORM\Entity(repositoryClass="ZONNY\Repositories\Account\FriendsLinkRepository")
 * @ORM\Table(name="friends_links", uniqueConstraints={@UniqueConstraint(name="friends_link_unique", columns={"user1_id", "user2_id"})})
 */
class FriendsLink
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="id")
     */private $id;
    /**
     * @var User $user1
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friendsLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user1;
    /**
     * @var User $user2
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friendsLinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user2;
    /**
     * @ORM\Column(type="integer", nullable=true, name="facebook_mutual_friends")
     */
    private $facebookMutualFriends;
    /**
     * @ORM\Column(type="integer", nullable=true, name="facebook_mutual_likes")
     */
    private $facebookMutualLikes;
    /**
     * @ORM\Column(type="boolean", name="authorization_user_1")
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
    public function getUser1() : ?User
    {
        return $this->user1;
    }

    /**
     * @param mixed $user1
     * @return FriendsLink
     */
    public function setUser1(User $user1 = null)
    {
        if($this->user1 !== null){
            $this->user1->removeFriendsLink($this);
        }
        if($user1 !== null){
            $user1->addFriendsLink($this);
        }

        $this->user1 = $user1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser2()
    {
        return $this->user2;
    }

    /**
     * @param mixed $user2
     * @return FriendsLink
     */
    public function setUser2(User $user2 = null)
    {
        if($this->user2 !== null){
            $this->user2->removeFriendsLink($this);
        }
        if($user2 !== null){
            $user2->addFriendsLink($this);
        }

        $this->user2 = $user2;
        return $this;
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
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 25/05/2018
 * Time: 22:00
 */

namespace ZONNY\Models\Event;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;

/**
 * Class EventMemberDetails
 * @package ZONNY\Models\Event
 * @ORM\Entity
 * @ORM\Table(name="event_member_details")
 */
class EventMemberDetails
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Event $event
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="event_member_details")
     */
    private $event;
    /**
     * @var User $invitedFriend
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="event_member_details")
     * @ORM\JoinColumn(name="invited_friend_id")
     */
    private $invitedFriend;
    /**
     * @ORM\Column(type="decimal", name="friend_latitude", nullable=true)
     */
    private $friendLatitude;
    /**
     * @ORM\Column(type="decimal", name="friend_longitude", nullable=true)
     */
    private $friendLongitude;
    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $response;
    /**
     * @ORM\Column(type="boolean", name="is_creator")
     */
    private $isCreator = false;
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
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     * @return EventMemberDetails
     */
    public function setEvent(Event $event)
    {
        if($this->event !== null){
            $this->event->removeEventMemberDetails($this);
        }
        if($event !== null){
            $event->addEventMemberDetails($this);
        }

        $this->event = $event;
        return $this;
    }

    /**
     * @return User
     */
    public function getInvitedFriend(): User
    {
        return $this->invitedFriend;
    }

    /**
     * @param User $invitedFriend
     * @return EventMemberDetails
     */
    public function setInvitedFriend(User $invitedFriend)
    {
        if($this->invitedFriend !== null){
            $this->invitedFriend->removeEventMemberDetails($this);
        }
        if($invitedFriend !== null){
            $invitedFriend->addEventMemberDetails($this);
        }

        $this->invitedFriend = $invitedFriend;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFriendLatitude()
    {
        return $this->friendLatitude;
    }

    /**
     * @param mixed $friendLatitude
     */
    public function setFriendLatitude($friendLatitude): void
    {
        $this->friendLatitude = $friendLatitude;
    }

    /**
     * @return mixed
     */
    public function getFriendLongitude()
    {
        return $this->friendLongitude;
    }

    /**
     * @param mixed $friendLongitude
     */
    public function setFriendLongitude($friendLongitude): void
    {
        $this->friendLongitude = $friendLongitude;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getisCreator()
    {
        return $this->isCreator;
    }

    /**
     * @param mixed $isCreator
     */
    public function setIsCreator($isCreator): void
    {
        $this->isCreator = $isCreator;
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
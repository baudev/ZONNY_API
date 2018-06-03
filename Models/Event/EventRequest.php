<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 17:03
 */

namespace ZONNY\Models\Event;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use ZONNY\Models\Account\User;

/**
 * Class EventRequest
 * @package ZONNY\Models\Event
 * @ORM\Entity
 * @ORM\Table(name="events_requests", uniqueConstraints={@UniqueConstraint(name="events_requests_unique", columns={"event_id", "user_id"})})
 */
class EventRequest
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Event $event
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="events_requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events_requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $response = 0;
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
     * @return EventRequest
     */
    public function setEvent(Event $event)
    {
        if($this->event !== null){
            $this->event->removeEventRequest($this);
        }
        if($event !== null){
            $event->addEventRequest($this);
        }

        $this->event = $event;
        return $this;
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
     * @return EventRequest
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeEventRequest($this);
        }
        if($user !== null){
            $user->addEventRequest($this);
        }

        $this->user = $user;
        return $this;
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
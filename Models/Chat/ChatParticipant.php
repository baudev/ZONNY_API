<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:46
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;

/**
 * Class ChatParticipant
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="chat_participants")
 */
class ChatParticipant
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $event;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chat_participants")
     */
    private $user;
    /**
     * @ORM\Column(type="datetimetz", nullable=true, name="typing_datetime")
     */
    private $typingDatetime;
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
     */
    public function setEvent($event): void
    {
        $this->event = $event;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ChatParticipant
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeChatParticipant($this);
        }
        if($user !== null){
            $user->addChatParticipant($this);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTypingDatetime()
    {
        return $this->typingDatetime;
    }

    /**
     * @param mixed $typingDatetime
     */
    public function setTypingDatetime($typingDatetime): void
    {
        $this->typingDatetime = $typingDatetime;
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
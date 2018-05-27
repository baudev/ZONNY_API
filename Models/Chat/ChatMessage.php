<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:51
 */

namespace ZONNY\Models\Chat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;
use ZONNY\Models\Event\Event;

/**
 * Class ChatMessage
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="chat_messages")
 */
class ChatMessage
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chat_messages")
     */
    private $user;
    /**
     * @var Event $event
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="chat_messages")
     */
    private $event;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * Foreign keys
     */
    /**
     * @ORM\OneToMany(targetEntity=State::class, cascade={"persist", "remove"}, mappedBy="chat_messages")
     */
    private $states;

    public function __construct()
    {
        $this->states = new ArrayCollection();
    }

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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return ChatMessage
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeChatMessage($this);
        }
        if($user !== null){
            $user->addChatMessage($this);
        }

        $this->user = $user;
        return $this;
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
     * @return ChatMessage
     */
    public function setEvent(Event $event)
    {
        if($this->event !== null){
            $this->event->removeChatMessage($this);
        }
        if($event !== null){
            $event->addChatMessage($this);
        }

        $this->event = $event;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
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


    /**
     * @return array|null
     */
    public function getStates(): ?array {
        return $this->states->toArray();
    }

    /**
     * @param State $state
     * @return $this
     */
    public function addChatMessage(State $state){
        if(!$this->states->contains($state)){
            $this->states->add($state);
        }
        return $this;
    }

    /**
     * @param State $state
     * @return $this
     */
    public function removeChatMessage(State $state){
        if($this->states->contains($state)){
            $this->states->removeElement($state);
        }
        return $this;
    }

}
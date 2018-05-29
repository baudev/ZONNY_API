<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:53
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class State
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="states")
 */
class State
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var ChatMessage $message
     * @ORM\ManyToOne(targetEntity=ChatMessage::class, inversedBy="states")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;
    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $state;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return State
     */
    public function setMessage(ChatMessage $message)
    {
        if($this->message !== null){
            $this->message->removeChatMessage($this);
        }
        if($message !== null){
            $message->addChatMessage($this);
        }

        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
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
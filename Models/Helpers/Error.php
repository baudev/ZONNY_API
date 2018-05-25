<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 25/05/2018
 * Time: 13:39
 */

namespace ZONNY\Models\Helpers;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;

/**
 * Class Error
 * @package ZONNY\Models\Helpers
 * @ORM\Entity
 * @ORM\Table(name="errors")
 */
class Error
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;
    /**
     * @ORM\Column(type="text")
     */
    private $message;
    /**
     * @ORM\Column(type="integer")
     */
    private $code;
    /**
     * @ORM\Column(type="text")
     */
    private $variables;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="errors")
     */
    private $user;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param mixed $variables
     */
    public function setVariables($variables): void
    {
        $this->variables = $variables;
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
     * @return Error
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeError($this);
        }

        if($user !== null){
            $user->addError($this);
        }

        $this->user = $user;
        return $this;
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
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 25/05/2018
 * Time: 13:39
 */

namespace ZONNY\Models\Account;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Report
 * @package ZONNY\Models\Account
 * @ORM\Entity
 * @ORM\Table(name="reports")
 */
class Report
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var User $concernedUser
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reports")
     * @ORM\JoinColumn(name="concerned_user_id")
     */
    private $concernedUser;
    /**
     * @var User $byUser
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reports")
     * @ORM\JoinColumn(name="by_user_id")
     */
    private $byUser;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;
    /**
     * @ORM\Column(type="text")
     */
    private $message;
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
     * @return User
     */
    public function getConcernedUser(): User
    {
        return $this->concernedUser;
    }

    /**
     * @param User $concernedUser
     * @return Report
     */
    public function setConcernedUser(User $concernedUser)
    {
        if($this->concernedUser !== null){
            $this->concernedUser->removeError($this);
        }

        if($concernedUser !== null){
            $concernedUser->addError($this);
        }

        $this->concernedUser = $concernedUser;
        return $this;
    }

    /**
     * @return User
     */
    public function getByUser(): User
    {
        return $this->byUser;
    }

    /**
     * @param User $byUser
     * @return Report
     */
    public function setByUser(User $byUser)
    {
        if($this->byUser !== null){
            $this->byUser->removeError($this);
        }

        if($byUser !== null){
            $byUser->addError($this);
        }

        $this->byUser = $byUser;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
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
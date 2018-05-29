<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:48
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use ZONNY\Models\Account\User;

/**
 * Class PendingOperation
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="pending_operations", uniqueConstraints={@UniqueConstraint(name="pending_operations_unique", columns={"user_id", "operation_id_for_user"})})
 */
class PendingOperation
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pending_operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\Column(type="bigint", name="operation_id_for_user")
     */
    private $operationIdForUser;
    /**
     * @ORM\Column(type="text", name="json_content")
     */
    private $jsonContent;
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
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return PendingOperation
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removePendingOperation($this);
        }
        if($user !== null){
            $user->addPendingOperation($this);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationIdForUser()
    {
        return $this->operationIdForUser;
    }

    /**
     * @param mixed $operationIdForUser
     */
    public function setOperationIdForUser($operationIdForUser): void
    {
        $this->operationIdForUser = $operationIdForUser;
    }

    /**
     * @return mixed
     */
    public function getJsonContent()
    {
        return $this->jsonContent;
    }

    /**
     * @param mixed $jsonContent
     */
    public function setJsonContent($jsonContent): void
    {
        $this->jsonContent = $jsonContent;
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
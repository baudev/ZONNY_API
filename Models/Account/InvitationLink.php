<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:16
 */

namespace ZONNY\Models\Account;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class InvitationLink
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="invitation_links", uniqueConstraints={@UniqueConstraint(name="invitation_links_unique", columns={"user_id", "used_by_user_id"})})
 */
class InvitationLink
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitation_links")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitation_links")
     * @ORM\JoinColumn(name="used_by_user_id")
     */
    private $usedByUser;
    /**
     * @ORM\Column(type="string", length=255, unique=true, name="token_id")
     */
    private $tokenId;
    /**
     * @ORM\Column(type="boolean", name="used")
     */
    private $used = false;
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
     * @return InvitationLink
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeInvitationLink($this);
        }
        if($user !== null){
            $user->addInvitationLink($this);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUsedByUser(): User
    {
        return $this->usedByUser;
    }

    /**
     * @param User $usedByUser
     * @return InvitationLink
     */
    public function setUsedByUser(User $usedByUser)
    {
        if($this->usedByUser !== null){
            $this->usedByUser->removeInvitationLink($this);
        }
        if($usedByUser !== null){
            $usedByUser->addInvitationLink($this);
        }

        $this->usedByUser = $usedByUser;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * @param mixed $tokenId
     */
    public function setTokenId($tokenId): void
    {
        $this->tokenId = $tokenId;
    }

    /**
     * @return mixed
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * @param mixed $used
     */
    public function setUsed($used): void
    {
        $this->used = $used;
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
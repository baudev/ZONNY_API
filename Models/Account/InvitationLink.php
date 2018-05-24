<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:16
 */

namespace ZONNY\Models\Account;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class InvitationLink
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="invitation_links")
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
     */
    private $user;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $tokenId;
    /**
     * @ORM\Column(type="boolean")
     */
    private $used = false;
    /**
     * @ORM\Column(type="datetimetz")
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
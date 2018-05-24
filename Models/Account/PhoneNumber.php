<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:40
 */

namespace ZONNY\Models\Account;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PhoneNumber
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="phone_numbers")
 */
class PhoneNumber
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
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $phoneNumbers;
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
     * @return PhoneNumber
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removePhoneNumber($this);
        }
        if($user !== null){
            $user->addPhoneNumber($this);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * @param mixed $phoneNumbers
     */
    public function setPhoneNumbers($phoneNumbers): void
    {
        $this->phoneNumbers = $phoneNumbers;
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
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:41
 */

namespace ZONNY\Models\Account;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Class Subscriptions
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="subscriptions", uniqueConstraints={@UniqueConstraint(name="subscription_unique", columns={"user_id", "followed_id"})})
 */
class Subscription
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptions")
     */
    private $user;
    /**
     * @var User $followed
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptions")
     */
    private $followed;
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Subscription
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeSubscription($this);
        }
        if($user !== null){
            $user->addSubscription($this);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFollowed()
    {
        return $this->followed;
    }

    /**
     * @param mixed $followed
     * @return Subscription
     */
    public function setFollowed($followed)
    {
        if($this->followed !== null){
            $this->followed->removeSubscription($this);
        }
        if($followed !== null){
            $followed->addSubscription($this);
        }

        $this->followed = $followed;
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
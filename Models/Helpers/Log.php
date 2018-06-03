<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 25/05/2018
 * Time: 13:39
 */

namespace ZONNY\Models\Helpers;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;
use ZONNY\Utils\Application;
use ZONNY\Utils\Database;

/**
 * Class Log
 * @package ZONNY\Models\Helpers
 * @ORM\Entity
 * @ORM\Table(name="logs")
 */
class Log
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
     * @ORM\Column(type="string", length=255, name="url_request")
     */
    private $urlRequest;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $ip;
    /**
     * @ORM\Column(type="boolean", name="hack_attempt")
     */
    private $hackAttempt = false;
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
    public function getUrlRequest()
    {
        return $this->urlRequest;
    }

    /**
     * @param mixed $urlRequest
     */
    public function setUrlRequest($urlRequest): void
    {
        $this->urlRequest = $urlRequest;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Log
     */
    public function setUser(User $user)
    {
        if($this->user !== null){
            $this->user->removeLog($this);
        }

        if($user !== null){
            $user->addLog($this);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getHackAttempt()
    {
        return $this->hackAttempt;
    }

    /**
     * @param mixed $hackAttempt
     */
    public function setHackAttempt($hackAttempt): void
    {
        $this->hackAttempt = $hackAttempt;
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
     * Ajoute le log à la base de données
     * @throws \Doctrine\ORM\ORMException
     */
    public function addToDatabase(){
        $this->setType(Application::getApp()->request->getMethod());
        $this->setUrlRequest(Application::getApp()->request->getResourceUri());
        $this->setUser(Application::getUser());
        $this->setIp(Application::getApp()->request->getIp());
        $this->setCreationDatetime(new Datetime());
        $entityManager = Database::getEntityManager();
        $entityManager->persist($this);
        $entityManager->flush();
    }

}
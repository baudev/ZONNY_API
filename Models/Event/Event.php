<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 25/05/2018
 * Time: 21:42
 */

namespace ZONNY\Models\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;

/**
 * Class Event
 * @package ZONNY\Models\Event
 * @ORM\Entity
 * @ORM\Table(name="events")
 */
class Event
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
    /**
     * @ORM\Column(type="text", nullable=true, name="picture_url")
     */
    private $pictureUrl;
    /**
     * @ORM\Column(type="decimal")
     */
    private $latitude;
    /**
     * @ORM\Column(type="decimal")
     */
    private $longitude;
    /**
     * @ORM\Column(type="datetimetz", name="start_datetime")
     */
    private $startDatetime;
    /**
     * @ORM\Column(type="datetimetz", name="end_datetime")
     */
    private $endDatetime;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="events")
     */
    private $creator;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $information;
    /**
     * @ORM\Column(type="boolean", name="with_location")
     */
    private $withLocation = false;
    /**
     * @ORM\Column(type="boolean", name="is_public")
     */
    private $isPublic = false;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @ORM\JoinColumn(name="from_suggestion_id")
     */
    private $fromSuggestion;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * Foreign keys
     */
    /**
     * @ORM\OneToMany(targetEntity=EventMemberDetails::class, cascade={"persist", "remove"}, mappedBy="events")
     */
    private $eventMemberDetails;

    public function __construct()
    {
        $this->eventMemberDetails = new ArrayCollection();
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param mixed $pictureUrl
     */
    public function setPictureUrl($pictureUrl): void
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }

    /**
     * @param mixed $startDatetime
     */
    public function setStartDatetime($startDatetime): void
    {
        $this->startDatetime = $startDatetime;
    }

    /**
     * @return mixed
     */
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

    /**
     * @param mixed $endDatetime
     */
    public function setEndDatetime($endDatetime): void
    {
        $this->endDatetime = $endDatetime;
    }

    /**
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     * @return Event
     */
    public function setCreator(User $creator)
    {
        if($this->creator !== null){
            $this->creator->removeEvent($this);
        }
        if($creator !== null){
            $creator->addEvent($this);
        }

        $this->creator = $creator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param mixed $information
     */
    public function setInformation($information): void
    {
        $this->information = $information;
    }

    /**
     * @return mixed
     */
    public function getWithLocation()
    {
        return $this->withLocation;
    }

    /**
     * @param mixed $withLocation
     */
    public function setWithLocation($withLocation): void
    {
        $this->withLocation = $withLocation;
    }

    /**
     * @return mixed
     */
    public function getisPublic()
    {
        return $this->isPublic;
    }

    /**
     * @param mixed $isPublic
     */
    public function setIsPublic($isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    /**
     * @return mixed
     */
    public function getFromSuggestion()
    {
        return $this->fromSuggestion;
    }

    /**
     * @param mixed $fromSuggestion
     */
    public function setFromSuggestion($fromSuggestion): void
    {
        $this->fromSuggestion = $fromSuggestion;
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
    public function getEventMemberDetails(): ?array {
        return $this->eventMemberDetails->toArray();
    }

    /**
     * @param EventMemberDetails $eventMemberDetails
     * @return $this
     */
    public function addEventMemberDetails(EventMemberDetails $eventMemberDetails){
        if(!$this->eventMemberDetails->contains($eventMemberDetails)){
            $this->eventMemberDetails->add($eventMemberDetails);
        }
        return $this;
    }

    /**
     * @param EventMemberDetails $eventMemberDetails
     * @return $this
     */
    public function removeEventMemberDetails(EventMemberDetails $eventMemberDetails){
        if($this->eventMemberDetails->contains($eventMemberDetails)){
            $this->eventMemberDetails->removeElement($eventMemberDetails);
        }
        return $this;
    }

}
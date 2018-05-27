<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 17:24
 */

namespace ZONNY\Models;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Models\Account\User;
use ZONNY\Models\Suggestion\DayTime;
use ZONNY\Models\Suggestion\SuggestionCategory;

/**
 * Class Suggestion
 * @package ZONNY\Models
 * @ORM\Entity
 * @ORM\Table(name="suggestions")
 */
class Suggestion
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
     * @ORM\Column(type="datetimetz", name="start_time", nullable=true)
     */
    private $startTime;
    /**
     * @ORM\Column(type="datetimetz", name="end_time", nullable=true)
     */
    private $endTime;
    /**
     * @ORM\Column(type="boolean", name="is_recurrent")
     */
    private $isRecurrent = false;
    /**
     * @ORM\Column(type="string", length=20, nullable=true, name="recurring_type")
     */
    private $recurringType;
    /**
     * @var User $user
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="suggestions")
     */
    private $creator;
    /**
     * @ORM\Column(type="boolean", name="anonymous_creator")
     */
    private $anonymousCreator = true;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $information;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * Foreign keys
     */
    /**
     * @ORM\OneToMany(targetEntity=SuggestionCategory::class, cascade={"persist", "remove"}, mappedBy="suggestions")
     */
    private $suggestionCategories;
    /**
     * @ORM\OneToMany(targetEntity=DayTime::class, cascade={"persist", "remove"}, mappedBy="suggestions")
     */
    private $dayTimes;

    public function __construct()
    {
        $this->suggestionCategories = new ArrayCollection();
        $this->dayTimes = new ArrayCollection();
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
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return mixed
     */
    public function getisRecurrent()
    {
        return $this->isRecurrent;
    }

    /**
     * @param mixed $isRecurrent
     */
    public function setIsRecurrent($isRecurrent): void
    {
        $this->isRecurrent = $isRecurrent;
    }

    /**
     * @return mixed
     */
    public function getRecurringType()
    {
        return $this->recurringType;
    }

    /**
     * @param mixed $recurringType
     */
    public function setRecurringType($recurringType): void
    {
        $this->recurringType = $recurringType;
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
     * @return Suggestion
     */
    public function setCreator(User $creator)
    {
        if($this->creator !== null){
            $this->creator->removeS($this);
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
    public function getAnonymousCreator()
    {
        return $this->anonymousCreator;
    }

    /**
     * @param mixed $anonymousCreator
     */
    public function setAnonymousCreator($anonymousCreator): void
    {
        $this->anonymousCreator = $anonymousCreator;
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
    public function getSuggestionCategories(): ?array {
        return $this->suggestionCategories->toArray();
    }

    /**
     * @param SuggestionCategory $suggestionCategory
     * @return $this
     */
    public function addSuggestionCategory(SuggestionCategory $suggestionCategory){
        if(!$this->suggestionCategories->contains($suggestionCategory)){
            $this->suggestionCategories->add($suggestionCategory);
        }
        return $this;
    }

    /**
     * @param SuggestionCategory $suggestionCategory
     * @return $this
     */
    public function removeSuggestionCategory(SuggestionCategory $suggestionCategory){
        if($this->suggestionCategories->contains($suggestionCategory)){
            $this->suggestionCategories->removeElement($suggestionCategory);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getDayTimes(): ?array {
        return $this->dayTimes->toArray();
    }

    /**
     * @param DayTime $dayTime
     * @return $this
     */
    public function addDayTime(DayTime $dayTime){
        if(!$this->dayTimes->contains($dayTime)){
            $this->dayTimes->add($dayTime);
        }
        return $this;
    }

    /**
     * @param DayTime $dayTime
     * @return $this
     */
    public function removeDayTime(DayTime $dayTime){
        if($this->dayTimes->contains($dayTime)){
            $this->dayTimes->removeElement($dayTime);
        }
        return $this;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 27/05/2018
 * Time: 18:00
 */

namespace ZONNY\Models\Suggestion;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use ZONNY\Models\Suggestion\Suggestion;

/**
 * Class DayTime
 * @package ZONNY\Models\Suggestion
 * @ORM\Entity
 * @ORM\Table(name="day_times", uniqueConstraints={@UniqueConstraint(name="day_times_unique", columns={"suggestion_id", "day"})})
 */
class DayTime
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Suggestion $suggestion
     * @ORM\ManyToOne(targetEntity=Suggestion::class, inversedBy="suggestion_categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $suggestion;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $day;
    /**
     * @ORM\Column(type="time", name="day_open")
     */
    private $dayOpen;
    /**
     * @ORM\Column(type="time", name="day_close")
     */
    private $dayClose;
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
    public function getSuggestion()
    {
        return $this->suggestion;
    }

    /**
     * @param mixed $suggestion
     * @return DayTime
     */
    public function setSuggestion(Suggestion $suggestion)
    {
        if($this->suggestion !== null){
            $this->suggestion->removeDayTime($this);
        }
        if($suggestion !== null){
            $suggestion->addDayTime($this);
        }

        $this->suggestion = $suggestion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day): void
    {
        $this->day = $day;
    }

    /**
     * @return mixed
     */
    public function getDayOpen()
    {
        return $this->dayOpen;
    }

    /**
     * @param mixed $dayOpen
     */
    public function setDayOpen($dayOpen): void
    {
        $this->dayOpen = $dayOpen;
    }

    /**
     * @return mixed
     */
    public function getDayClose()
    {
        return $this->dayClose;
    }

    /**
     * @param mixed $dayClose
     */
    public function setDayClose($dayClose): void
    {
        $this->dayClose = $dayClose;
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
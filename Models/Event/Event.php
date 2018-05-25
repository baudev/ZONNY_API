<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 25/05/2018
 * Time: 21:42
 */

namespace ZONNY\Models\Event;
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
     * @ORM\Column(type="text")
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

}
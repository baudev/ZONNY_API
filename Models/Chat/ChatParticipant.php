<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:46
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ChatParticipant
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="chat_participants")
 */
class ChatParticipant
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $eventId;
    /**
     * @ORM\Column(type="integer")
     */
    private $userId;
    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $typingDatetime;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;
}
<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:51
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ChatMessage
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="chat_messages")
 */
class ChatMessage
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
    private $userId;
    /**
     * @ORM\Column(type="integer")
     */
    private $eventId;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $type;
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;

}
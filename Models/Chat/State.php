<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:53
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class State
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="states")
 */
class State
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
    private $messageId;
    /**
     * @ORM\Column(type="integer", length=1, nullable=true)
     */
    private $state;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;

}
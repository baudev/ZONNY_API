<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:48
 */

namespace ZONNY\Models\Chat;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PendingOperation
 * @package ZONNY\Models\Chat
 * @ORM\Entity
 * @ORM\Table(name="pending_operations")
 */
class PendingOperation
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
     * @ORM\Column(type="string")
     */
    private $operationIdForUser;
    /**
     * @ORM\Column(type="text")
     */
    private $jsonContent;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;



}
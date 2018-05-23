<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:41
 */

namespace ZONNY\Models\Accounts;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Suscriptions
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="suscriptions")
 */
class Suscription
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $userId;
    /**
     * @ORM\Column(type="integer")
     */
    private $followedId;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;


}
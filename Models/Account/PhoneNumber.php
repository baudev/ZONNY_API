<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:40
 */

namespace ZONNY\Models\Account;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PhoneNumber
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="phone_numbers")
 */
class PhoneNumber
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
     * @ORM\Column(type="string", length=30)
     */
    private $phoneNumbers;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;

}
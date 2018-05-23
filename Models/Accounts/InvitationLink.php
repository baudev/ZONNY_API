<?php
/**
 * Created by PhpStorm.
 * User: Baudev
 * Date: 23/05/2018
 * Time: 13:16
 */

namespace ZONNY\Models\Accounts;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class InvitationLink
 * @package ZONNY\Models\Accounts
 * @ORM\Entity
 * @ORM\Table(name="invitation_links")
 */
class InvitationLink
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
     * @ORM\Column(type="string", length=255)
     */
    private $tokenId;
    /**
     * @ORM\Column(type="boolean")
     */
    private $used = false;
    /**
     * @ORM\Column(type="datetimetz")
     */
    private $creationDatetime;

}
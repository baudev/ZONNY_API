<?php
namespace ZONNY\Models\Accounts;

use Doctrine\DBAL\Schema\Column;
use Doctrine\ORM\Mapping as ORM;
use ZONNY\Utils\ApiKey;
use ZONNY\Utils\Application;
use ZONNY\Utils\Database;
use ZONNY\Utils\DatetimeISO8601;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\Functions;
use ZONNY\Utils\PublicError;


/**
 * Class User
 * @package ZONNY\Models\Accounts
 * @ORM\Entity()
 * @ORM\Table()
 */
class User implements \JsonSerializable
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=50, name="name")
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=25, nullable=true, name="first_name")
     */
    private $firstName;
    /**
     * @ORM\Column(type="string", length=25, nullable=true, name="last_name")
     */
    private $lastName;
    /**
     * @ORM\Column(type="text", length=25, nullable=true, name="profile_picture_url")
     */
    private $profilePictureUrl;
    /**
     * @ORM\Column(type="string", length=20, nullable=true, unique=true, name="pseudo")
     */
    private $pseudo;
    /**
     * @ORM\Column(type="datetimetz", nullable=true, name="unavailable")
     */
    private $unavailable;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="gcm_registration_id")
     */
    private $gcmRegistrationId;
    /**
     * @ORM\Column(type="string", length=150, name="key_app")
     */
    private $keyApp;
    /**
     * @ORM\Column(type="decimal", nullable=true, name="latitude")
     */
    private $latitude;
    /**
     * @ORM\Column(type="decimal", nullable=true, name="longitude")
     */
    private $longitude;
    /**
     * @ORM\Column(type="decimal", nullable=true, name="last_latitude")
     */
    private $lastLatitude;
    /**
     * @ORM\Column(type="decimal", nullable=true, name="last_longitude")
     */
    private $lastLongitude;
    /**
     * @ORM\Column(type="decimal", nullable=true, name="distance_previous_location")
     */
    private $distancePreviousLocation;
    /**
     * @ORM\Column(type="datetimetz", nullable=true, name="last_location_check_up")
     */
    private $lastLocationCheckUp;
    /**
     * @ORM\Column(type="datetimetz", nullable=true, name="last_internet_check_up")
     */
    private $lastInternetCheckUp;
    /**
     * @ORM\Column(type="integer", length=1, name="platform")
     */
    private $platform;
    /**
     * @ORM\Column(type="boolean", name="facebook_connection")
     */
    private $facebookConnection;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="facebook_access_token")
     */
    private $facebookAccessToken;
    /**
     * @ORM\Column(type="bigint", nullable=true, name="facebook_user_id")
     */
    private $facebookUserId;
    /**
     * @ORM\Column(type="boolean", name="sms_connection")
     */
    private $smsConnection;
    /**
     * @ORM\Column(type="string", length=30, nullable=true, name="phone_number")
     */
    private $phoneNumber;
    /**
     * @ORM\Column(type="boolean", name="email_connection")
     */
    private $emailConnection;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="email")
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="password")
     */
    private $password;
    /**
     * @ORM\Column(type="boolean", name="anonymous_connection")
     */
    private $anonymousConnection;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
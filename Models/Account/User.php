<?php
namespace ZONNY\Models\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Schema\Column;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;


/**
 * Class User
 * @package ZONNY\Models\Accounts
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User implements \JsonSerializable
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(type="string", length=150, name="key_app", unique=true)
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
    private $facebookConnection = false;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="facebook_access_token")
     */
    private $facebookAccessToken;
    /**
     * @ORM\Column(type="bigint", nullable=true, name="facebook_user_id", unique=true)
     */
    private $facebookUserId;
    /**
     * @ORM\Column(type="boolean", name="sms_connection")
     */
    private $smsConnection = false;
    /**
     * @ORM\Column(type="string", length=30, nullable=true, name="phone_number", unique=true)
     */
    private $phoneNumber;
    /**
     * @ORM\Column(type="boolean", name="email_connection")
     */
    private $emailConnection = false;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="email", unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="password")
     */
    private $password;
    /**
     * @ORM\Column(type="boolean", name="anonymous_connection")
     */
    private $anonymousConnection = false;
    /**
     * @ORM\Column(type="datetimetz", name="creation_datetime")
     */
    private $creationDatetime;

    /**
     * Foreign keys
     */
    /**
     * @ORM\OneToMany(targetEntity=FriendsLink::class, cascade={"persist", "remove"}, mappedBy="users")
     */
    private $friends_links;
    /**
     * @ORM\OneToMany(targetEntity=InvitationLink::class, cascade={"persist", "remove"}, mappedBy="users")
     */
    private $invitation_links;


    public function __construct()
    {
        $this->friends_links = new ArrayCollection();
        $this->invitation_links = new ArrayCollection();
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getProfilePictureUrl()
    {
        return $this->profilePictureUrl;
    }

    /**
     * @param mixed $profilePictureUrl
     */
    public function setProfilePictureUrl($profilePictureUrl): void
    {
        $this->profilePictureUrl = $profilePictureUrl;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getUnavailable()
    {
        return $this->unavailable;
    }

    /**
     * @param mixed $unavailable
     */
    public function setUnavailable($unavailable): void
    {
        $this->unavailable = $unavailable;
    }

    /**
     * @return mixed
     */
    public function getGcmRegistrationId()
    {
        return $this->gcmRegistrationId;
    }

    /**
     * @param mixed $gcmRegistrationId
     */
    public function setGcmRegistrationId($gcmRegistrationId): void
    {
        $this->gcmRegistrationId = $gcmRegistrationId;
    }

    /**
     * @return mixed
     */
    public function getKeyApp()
    {
        return $this->keyApp;
    }

    /**
     * @param mixed $keyApp
     */
    public function setKeyApp($keyApp): void
    {
        $this->keyApp = $keyApp;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLastLatitude()
    {
        return $this->lastLatitude;
    }

    /**
     * @param mixed $lastLatitude
     */
    public function setLastLatitude($lastLatitude): void
    {
        $this->lastLatitude = $lastLatitude;
    }

    /**
     * @return mixed
     */
    public function getLastLongitude()
    {
        return $this->lastLongitude;
    }

    /**
     * @param mixed $lastLongitude
     */
    public function setLastLongitude($lastLongitude): void
    {
        $this->lastLongitude = $lastLongitude;
    }

    /**
     * @return mixed
     */
    public function getDistancePreviousLocation()
    {
        return $this->distancePreviousLocation;
    }

    /**
     * @param mixed $distancePreviousLocation
     */
    public function setDistancePreviousLocation($distancePreviousLocation): void
    {
        $this->distancePreviousLocation = $distancePreviousLocation;
    }

    /**
     * @return mixed
     */
    public function getLastLocationCheckUp()
    {
        return $this->lastLocationCheckUp;
    }

    /**
     * @param mixed $lastLocationCheckUp
     */
    public function setLastLocationCheckUp($lastLocationCheckUp): void
    {
        $this->lastLocationCheckUp = $lastLocationCheckUp;
    }

    /**
     * @return mixed
     */
    public function getLastInternetCheckUp()
    {
        return $this->lastInternetCheckUp;
    }

    /**
     * @param mixed $lastInternetCheckUp
     */
    public function setLastInternetCheckUp($lastInternetCheckUp): void
    {
        $this->lastInternetCheckUp = $lastInternetCheckUp;
    }

    /**
     * @return mixed
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param mixed $platform
     */
    public function setPlatform($platform): void
    {
        $this->platform = $platform;
    }

    /**
     * @return mixed
     */
    public function getFacebookConnection()
    {
        return $this->facebookConnection;
    }

    /**
     * @param mixed $facebookConnection
     */
    public function setFacebookConnection($facebookConnection): void
    {
        $this->facebookConnection = $facebookConnection;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken): void
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return mixed
     */
    public function getFacebookUserId()
    {
        return $this->facebookUserId;
    }

    /**
     * @param mixed $facebookUserId
     */
    public function setFacebookUserId($facebookUserId): void
    {
        $this->facebookUserId = $facebookUserId;
    }

    /**
     * @return mixed
     */
    public function getSmsConnection()
    {
        return $this->smsConnection;
    }

    /**
     * @param mixed $smsConnection
     */
    public function setSmsConnection($smsConnection): void
    {
        $this->smsConnection = $smsConnection;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getEmailConnection()
    {
        return $this->emailConnection;
    }

    /**
     * @param mixed $emailConnection
     */
    public function setEmailConnection($emailConnection): void
    {
        $this->emailConnection = $emailConnection;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAnonymousConnection()
    {
        return $this->anonymousConnection;
    }

    /**
     * @param mixed $anonymousConnection
     */
    public function setAnonymousConnection($anonymousConnection): void
    {
        $this->anonymousConnection = $anonymousConnection;
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

    /**
     * @return mixed
     */
    public function getFriendsLinks(): ?array
    {
        return $this->friends_links->toArray();
    }

    /**
     * @param FriendsLink $friendsLink
     * @return User
     */
    public function addFriendsLink(FriendsLink $friendsLink)
    {
        if(!$this->friends_links->contains($friendsLink)) {
            $this->friends_links->add($friendsLink);
        }
        return $this;
    }

    /**
     * @param FriendsLink $friendsLink
     * @return User
     */
    public function removeFriendsLink(FriendsLink $friendsLink){
        if($this->friends_links->contains($friendsLink)){
            $this->friends_links->removeElement($friendsLink);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvitationLinks(): ?array
    {
        return $this->invitation_links->toArray();
    }

    /**
     * @param InvitationLink $invitation_link
     * @return User
     */
    public function addInvitationLink(InvitationLink $invitation_link)
    {
        if(!$this->invitation_links->contains($invitation_link)) {
            $this->invitation_links->add($invitation_link);
        }
        return $this;
    }

    /**
     * @param InvitationLink $friendsLink
     * @return User
     */
    public function removeInvitationLink(InvitationLink $friendsLink){
        if($this->invitation_links->contains($friendsLink)) {
            $this->invitation_links->removeElement($friendsLink);
        }
        return $this;
    }



}
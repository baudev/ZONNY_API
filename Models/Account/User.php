<?php
namespace ZONNY\Models\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Schema\Column;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use ZONNY\Models\Chat\ChatMessage;
use ZONNY\Models\Chat\ChatParticipant;
use ZONNY\Models\Chat\PendingOperation;
use ZONNY\Models\Event\Event;
use ZONNY\Models\Event\EventMemberDetails;
use ZONNY\Models\Event\EventRequest;
use ZONNY\Models\Helpers\Error;
use ZONNY\Models\Helpers\Log;
use ZONNY\Models\Suggestion\Suggestion;
use ZONNY\Repositories\Account\UserRepository;
use ZONNY\Repositories\Event\EventMemberDetailsRepository;


/**
 * Class User
 * @package ZONNY\Models\Accounts
 * @ORM\Entity(repositoryClass="ZONNY\Repositories\Account\UserRepository")
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
     * @ORM\OneToMany(targetEntity=FriendsLink::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $friendsLinks;
    /**
     * @ORM\OneToMany(targetEntity=InvitationLink::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $invitationLinks;
    /**
     * @ORM\OneToMany(targetEntity=InvitationLink::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $invitationLinksUsed;
    /**
     * @ORM\OneToMany(targetEntity=PhoneNumber::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $phoneNumbers;
    /**
     * @ORM\OneToMany(targetEntity=Subscription::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $subscriptions;
    /**
     * @ORM\OneToMany(targetEntity=Error::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $errors;
    /**
     * @ORM\OneToMany(targetEntity=Log::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $logs;
    /**
     * @ORM\OneToMany(targetEntity=Report::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $reportsConcerned;
    /**
     * @ORM\OneToMany(targetEntity=Report::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $reportsByUser;
    /**
     * @ORM\OneToMany(targetEntity=ChatParticipant::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $chatParticipants;
    /**
     * @ORM\OneToMany(targetEntity=PendingOperation::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $pendingOperations;
    /**
     * @ORM\OneToMany(targetEntity=Event::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $events;
    /**
     * @ORM\OneToMany(targetEntity=EventMemberDetails::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $eventMemberDetails;
    /**
     * @ORM\OneToMany(targetEntity=ChatMessage::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $chatMessages;
    /**
     * @ORM\OneToMany(targetEntity=EventRequest::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $eventRequests;
    /**
     * @ORM\OneToMany(targetEntity=Suggestion::class, cascade={"persist", "remove"}, mappedBy="user")
     */
    private $suggestions;



    public function __construct()
    {
        $this->friendsLinks = new ArrayCollection();
        $this->invitationLinks = new ArrayCollection();
        $this->invitationLinksUsed = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->errors = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->reportsConcerned = new ArrayCollection();
        $this->reportsByUser = new ArrayCollection();
        $this->chatParticipants = new ArrayCollection();
        $this->pendingOperations = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->eventMemberDetails = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
        $this->eventRequests = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
    }

    /**
     * Return the user level
     * @return int
     * @throws \Doctrine\ORM\ORMException
     */
    public function getLevel() : int {
        $invitationsInfos = EventMemberDetailsRepository::getRepository()->getUserInvitationsNewerThanOneWeek($this);
        $level = 0;
        /** @var EventMemberDetails $invitationsInfo */
        foreach ($invitationsInfos as $key => $invitationsInfo){
            if($invitationsInfo->getisCreator()){
                $level += 6;
            }
            else if($invitationsInfo->getResponse() == 1){
                $level += 4;
            }
            else {
                $level += 2;
            }
        }

        // if the counter is lower than 10 over 100, we check if the user is newer than one week
        if($level <= 10){
            if($this->getCreationDatetime() > (new \DateTime())->modify('-7 days')){
                $level = 10;
            }
        }
        return $level;
    }

    /**
     * Return if the user has to update his location
     * @return bool
     */
    public function needToUpdateHisLocation() : bool {
        $current = new \DateTime();
        $lastLocalisation = $this->getLastLocationCheckUp();
        if (isset($lastLocalisation)) {
            $diff = $current->getTimestamp() - $lastLocalisation->getTimestamp();
            if($diff->s>NUMBER_SECONDS_MUST_RESEND_LOCATION){
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    /**
     * Return if the user is considered has unavailable or not (ghost mode).
     * @return bool
     */
    public function isUnavailable() : bool {
        if(!empty($this->getUnavailable())) {
            $current = new \DateTime();
            $unavailable = $this->getUnavailable();
            $diff = $unavailable->getTimestamp() - $current->getTimestamp();
            if($diff>0){
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function isCurrentlyToAnEvent() : bool {
        // we count
        $number_results = count(EventMemberDetailsRepository::getRepository()->getCurrentEventsWhereUserIsComing($this));
        return $number_results == 0 ? false : true;
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
        return $this->friendsLinks->toArray();
    }

    /**
     * @param FriendsLink $friendsLink
     * @return User
     */
    public function addFriendsLink(FriendsLink $friendsLink)
    {
        if(!$this->friendsLinks->contains($friendsLink)) {
            $this->friendsLinks->add($friendsLink);
        }
        return $this;
    }

    /**
     * @param FriendsLink $friendsLink
     * @return User
     */
    public function removeFriendsLink(FriendsLink $friendsLink){
        if($this->friendsLinks->contains($friendsLink)){
            $this->friendsLinks->removeElement($friendsLink);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvitationLinks(): ?array
    {
        return $this->invitationLinks->toArray();
    }

    /**
     * @param InvitationLink $invitationLink
     * @return User
     */
    public function addInvitationLink(InvitationLink $invitationLink)
    {
        if(!$this->invitationLinks->contains($invitationLink)) {
            $this->invitationLinks->add($invitationLink);
        }
        return $this;
    }

    /**
     * @param InvitationLink $invitationLink
     * @return User
     */
    public function removeInvitationLink(InvitationLink $invitationLink){
        if($this->invitationLinks->contains($invitationLink)) {
            $this->invitationLinks->removeElement($invitationLink);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInvitationLinksUsed(): ?array
    {
        return $this->invitationLinksUsed->toArray();
    }

    /**
     * @param InvitationLink $invitationLink
     * @return User
     */
    public function addInvitationLinkUsed(InvitationLink $invitationLink)
    {
        if(!$this->invitationLinksUsed->contains($invitationLink)) {
            $this->invitationLinksUsed->add($invitationLink);
        }
        return $this;
    }

    /**
     * @param InvitationLink $invitationLink
     * @return User
     */
    public function removeInvitationLinkUsed(InvitationLink $invitationLink){
        if($this->invitationLinksUsed->contains($invitationLink)) {
            $this->invitationLinksUsed->removeElement($invitationLink);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumbers(): ?array
    {
        return $this->phoneNumbers->toArray();
    }

    /**
     * @param PhoneNumber $phoneNumber
     * @return User
     */
    public function addPhoneNumber(PhoneNumber $phoneNumber)
    {
        if(!$this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->add($phoneNumber);
        }
        return $this;
    }

    /**
     * @param PhoneNumber $phoneNumber
     * @return User
     */
    public function removePhoneNumber(PhoneNumber $phoneNumber){
        if($this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->removeElement($phoneNumber);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscriptions(): ?array
    {
        return $this->subscriptions->toArray();
    }

    /**
     * @param Subscription $subscription
     * @return User
     */
    public function addSubscription(Subscription $subscription)
    {
        if(!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
        }
        return $this;
    }

    /**
     * @param Subscription $subscription
     * @return User
     */
    public function removeSubscription(Subscription $subscription){
        if($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getErrors(): ?array {
        return $this->errors->toArray();
    }

    /**
     * @param Error $error
     * @return $this
     */
    public function addError(Error $error){
        if(!$this->errors->contains($error)){
            $this->errors->add($error);
        }
        return $this;
    }

    /**
     * @param Error $error
     * @return $this
     */
    public function removeError(Error $error){
        if($this->errors->contains($error)){
            $this->errors->removeElement($error);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getLogs(): ?array {
        return $this->logs->toArray();
    }

    /**
     * @param Log $log
     * @return $this
     */
    public function addLog(Log $log){
        if(!$this->logs->contains($log)){
            $this->logs->add($log);
        }
        return $this;
    }

    /**
     * @param Log $log
     * @return $this
     */
    public function removeLog(Log $log){
        if($this->logs->contains($log)){
            $this->logs->removeElement($log);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getReportsConcerned(): ?array {
        return $this->reportsConcerned->toArray();
    }

    /**
     * @param Report $report
     * @return $this
     */
    public function addReportConcerned(Report $report){
        if(!$this->reportsConcerned->contains($report)){
            $this->reportsConcerned->add($report);
        }
        return $this;
    }

    /**
     * @param Report $report
     * @return $this
     */
    public function removeReportConcerned(Report $report){
        if($this->reportsConcerned->contains($report)){
            $this->reportsConcerned->removeElement($report);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getReportsByUser(): ?array {
        return $this->reportsByUser->toArray();
    }

    /**
     * @param Report $report
     * @return $this
     */
    public function addReportByUser(Report $report){
        if(!$this->reportsByUser->contains($report)){
            $this->reportsByUser->add($report);
        }
        return $this;
    }

    /**
     * @param Report $report
     * @return $this
     */
    public function removeReportByUser(Report $report){
        if($this->reportsByUser->contains($report)){
            $this->reportsByUser->removeElement($report);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getChatParticipants(): ?array {
        return $this->chatParticipants->toArray();
    }

    /**
     * @param ChatParticipant $chatParticipant
     * @return $this
     */
    public function addChatParticipant(ChatParticipant $chatParticipant){
        if(!$this->chatParticipants->contains($chatParticipant)){
            $this->chatParticipants->add($chatParticipant);
        }
        return $this;
    }

    /**
     * @param ChatParticipant $chatParticipant
     * @return $this
     */
    public function removeChatParticipant(ChatParticipant $chatParticipant){
        if($this->chatParticipants->contains($chatParticipant)){
            $this->chatParticipants->removeElement($chatParticipant);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPendingOperations(): ?array {
        return $this->pendingOperations->toArray();
    }

    /**
     * @param PendingOperation $pendingOperation
     * @return $this
     */
    public function addPendingOperation(PendingOperation $pendingOperation){
        if(!$this->pendingOperations->contains($pendingOperation)){
            $this->pendingOperations->add($pendingOperation);
        }
        return $this;
    }

    /**
     * @param PendingOperation $pendingOperation
     * @return $this
     */
    public function removePendingOperation(PendingOperation $pendingOperation){
        if($this->pendingOperations->contains($pendingOperation)){
            $this->pendingOperations->removeElement($pendingOperation);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getEvents(): ?array {
        return $this->events->toArray();
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function addEvent(Event $event){
        if(!$this->events->contains($event)){
            $this->events->add($event);
        }
        return $this;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function removeEvent(Event $event){
        if($this->events->contains($event)){
            $this->events->removeElement($event);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getEventMemberDetails(): ?array {
        return $this->eventMemberDetails->toArray();
    }

    /**
     * @param EventMemberDetails $eventMemberDetails
     * @return $this
     */
    public function addEventMemberDetails(EventMemberDetails $eventMemberDetails){
        if(!$this->eventMemberDetails->contains($eventMemberDetails)){
            $this->eventMemberDetails->add($eventMemberDetails);
        }
        return $this;
    }

    /**
     * @param EventMemberDetails $eventMemberDetails
     * @return $this
     */
    public function removeEventMemberDetails(EventMemberDetails $eventMemberDetails){
        if($this->eventMemberDetails->contains($eventMemberDetails)){
            $this->eventMemberDetails->removeElement($eventMemberDetails);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getChatMessages(): ?array {
        return $this->chatMessages->toArray();
    }

    /**
     * @param ChatMessage $chatMessage
     * @return $this
     */
    public function addChatMessage(ChatMessage $chatMessage){
        if(!$this->chatMessages->contains($chatMessage)){
            $this->chatMessages->add($chatMessage);
        }
        return $this;
    }

    /**
     * @param ChatMessage $chatMessage
     * @return $this
     */
    public function removeChatMessage(ChatMessage $chatMessage){
        if($this->chatMessages->contains($chatMessage)){
            $this->chatMessages->removeElement($chatMessage);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getEventRequests(): ?array {
        return $this->eventRequests->toArray();
    }

    /**
     * @param EventRequest $eventRequest
     * @return $this
     */
    public function addEventRequest(EventRequest $eventRequest){
        if(!$this->eventRequests->contains($eventRequest)){
            $this->eventRequests->add($eventRequest);
        }
        return $this;
    }

    /**
     * @param EventRequest $eventRequest
     * @return $this
     */
    public function removeEventRequest(EventRequest $eventRequest){
        if($this->eventRequests->contains($eventRequest)){
            $this->eventRequests->removeElement($eventRequest);
        }
        return $this;
    }

    /**
     * @return array|null
     */
    public function getSuggestions(): ?array {
        return $this->suggestions->toArray();
    }

    /**
     * @param Suggestion $suggestion
     * @return $this
     */
    public function addSuggestion(Suggestion $suggestion){
        if(!$this->suggestions->contains($suggestion)){
            $this->suggestions->add($suggestion);
        }
        return $this;
    }

    /**
     * @param Suggestion $suggestion
     * @return $this
     */
    public function removeSuggestion(Suggestion $suggestion){
        if($this->suggestions->contains($suggestion)){
            $this->suggestions->removeElement($suggestion);
        }
        return $this;
    }
}
<?php
namespace ZONNY\Models\Events;

use ZONNY\Models\Accounts\Friend;
use ZONNY\Models\Accounts\FriendLink;
use ZONNY\Models\Accounts\User;
use ZONNY\Utils\Application;
use ZONNY\Utils\Database;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\Functions;
use ZONNY\Utils\PublicError;

/**
 * @SWG\Definition(
 *   definition="Event",
 *   type="object",
 *    required={"id", "name", "creator_id", "latitude", "longitude", "start_time", "end_time", "percentage_remaining", "number_guests", "number_participants", "distance"}
 * )
 */
class Event implements \JsonSerializable
{

    public CONST IS_PUBLIC = 1;
    public CONST IS_NOT_PUBLIC = 0;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The primary id",
     *     example=13649
     * )
     */
    private $id;
    /**
     * @var string
     * @SWG\Property(
     *     description="Name.",
     *     example="Let's drink a beer !"
     * )
     */
    private $name;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The id of the creator",
     *     example=365
     * )
     */
    private $creator_id;
    private $creator_latitude;
    private $creator_longitude;
    private $public;
    /**
     * @var float
     * @SWG\Property(
     *     description="The latitude",
     *     example=43.264
     * )
     */
    private $latitude;

    /**
     * @var float
     * @SWG\Property(
     *     description="The longitude",
     *     example=3.65412
     * )
     */
    private $longitude;
    /**
     * @var datetime
     * @SWG\Property(
     *     description="When the event starts. Must be in the next 24h",
     *     example="2018-04-02 13:00:00"
     * )
     */
    private $start_time;
    /**
     * @var datetime
     * @SWG\Property(
     *     description="When the event ends. Must be in the next 24h after the beginning of the event",
     *     example="2018-04-02 18:30:00"
     * )
     */
    private $end_time;
    /**
     * @var string
     * @SWG\Property(
     *     description="More details given by the creator"
     * )
     */
    private $information;
    /**
     * @var string
     * @SWG\Property(
     *     description="URL of the event's picture",
     *     example="https://images.pexels.com/photos/681847/pexels-photo-681847.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
     * )
     */
    private $picture_url;
    /**
     * @var integer
     * @SWG\Property(
     *     description="Actual percentage remaining of the total duration of the event",
     *     example=63
     * )
     */
    private $percentage_remaining;
    /**
     * @var integer
     * @SWG\Property(
     *     description="Number of guests",
     *     example=6
     * )
     */
    private $number_guests;
    /**
     * @var integer
     * @SWG\Property(
     *     description="Number of participants",
     *     example=2
     * )
     */
    private $number_participants;
    private $creation_datetime;
    private $with_localisation;
    /**
     * @var float
     * @SWG\Property(
     *     description="Distance between friend and user",
     *     example=213.152
     * )
     */
    private $distance;
    // vérifie si l'utilisateur est autorisé à obtenir les information concernant l'évènement
    public $is_authorized;
    // vérifie si l'utilisateur est invité
    public $is_invited;

    public function __construct(?array $data=null)
    {
        if(!empty($data)){
            $this->hydrate($data);
        }
    }


    /**
     * Hydrate l'objet
     * @param $data
     */
    public function hydrate($data){
        if(!empty($data)) {
            foreach ($data as $key => $value) {
                // on convertie rend les noms de la base de données cohérent avec le nom de setters
                // ex: last_name devient LastName
                // met en majuscule la première lettre de tous les mots séparés par _
                $key = ucwords($key, "_");
                $key = preg_replace("#_#", "", $key);
                $method = 'set' . $key;
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }


    /**
     * Récupère les information concernant l'objet à partir de son id ou key_app
     * @throws PublicError
     */
    public function getFromDatabase():bool
    {
        // on vérifie d'abord si l'utilisateur a l'autorisation d'obtenir les information concernant l'évènement
        //on vérifie si l'utilisateur est invité à l'évènement ou non
        $event_detail = new EventMemberDetails();
        $event_detail->setInvitedFriendId(Application::getUser()->getId());
        $event_detail->setEventId($this->getId());
        if(!$event_detail->getFromDatabase()){
            $this->is_invited = false;
        }
        else {
            $this->is_invited = true;
            $this->is_authorized = true;
        }

        // on vérifie alors si le créateur de l'évènement est un ami de l'utilisateur si l'évènement est public
        if(!$this->is_invited) {
            $req_auth = Database::getDb()->prepare('SELECT creator_id, public from events WHERE id=:id');
            $req_auth->execute(array(
                "id" => $this->getId()
            ));
            $data = $req_auth->fetch();
            if ($data != false) {
                $this->hydrate(($data));
                // on vérifie si l'évènement est public
                if($this->getPublic()) {
                    // on vérifie la relation entre le créateur et l'utilisateur
                    $friend_link = new FriendLink();
                    $friend_link->setFriendId(Application::getUser()->getId());
                    $friend_link->setUserId($this->getCreatorId());
                    if ($friend_link->getFromDatabase()) {
                        // la relation existe
                        if ($friend_link->getRelation()) {
                            // le créateur considère l'utilisateur comme un bon ami
                            $this->is_authorized = true;
                        }
                        else {
                            // le créateur ne considère pas l'utilisateur comme un bon ami
                            $this->is_authorized = false;
                            return false;
                        }
                    } else {
                        // le créateur n'est pas ami avec l'utilisateur
                        $this->is_authorized = false;
                        return false;
                    }
                }
            } else {
                // l'évènement n'existe pas
                return false;
            }
        }
        $req = Database::getDb()->prepare('SELECT *, ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance from events WHERE id=:id');
        $req->execute(array(
            "id" => $this->getId(),
            "user_longitude" => Application::getUser()->getLongitude(),
            "user_latitude" => Application::getUser()->getLatitude()
        ));
        $data = $req->fetch();
        if($data!=false){
            $this->hydrate(($data));
            return true;
        }
        else {
            // l'évènement n'existe pas
            return false;
        }
    }

    /**
     * @throws PublicError
     */
    public function addToDataBase()
    {
        $req = Database::getDb()->prepare("INSERT INTO events (name, creator_id, creator_latitude, creator_longitude, public, latitude, longitude, start_time, end_time, information, picture_url, creation_datetime, with_localisation) VALUES (:name, :creator_id, :creator_latitude, :creator_longitude, :public, :latitude, :longitude, :start_time, :end_time, :information, :picture_url, :creation_datetime, :with_localisation)");

        $array = array();
        // on défini le tableau contenant l'ensemble des variables
        foreach ($this as $key => $value) {
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            if (method_exists($this, $method)) {
                switch ($key) {
                    case 'id':
                    case 'percentage_remaining':
                    case 'number_guests':
                    case 'number_participants':
                    case 'distance':
                        break;
                    case 'creation_datetime':
                        $array[$key] = date('Y-m-d H:i:s');
                        break;
                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }
        $req->execute($array);
        // on insère l'id de l'utilisateur
        $this->setId(Database::getDb()->lastInsertId());
    }

    public function deleteFromDataBase()
    {
        $req = Database::getDb()->prepare('DELETE from events WHERE id=?');
        $req->execute(array($this->getId()));
    }

    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE events SET name = COALESCE(:name, name), creator_id = COALESCE(:creator_id, creator_id), creator_latitude = COALESCE(:creator_latitude, creator_latitude), creator_longitude = COALESCE(:creator_longitude, creator_longitude), public = COALESCE(:public, public), latitude = COALESCE(:latitude, latitude), longitude = COALESCE(:longitude, longitude), start_time = COALESCE(:start_time, start_time), end_time = COALESCE(:end_time, end_time), information = COALESCE(:information, information), picture_url = COALESCE(:picture_url, picture_url), with_localisation = COALESCE(:with_localisation, with_localisation) WHERE id = :id');

        $array = array();
        // on défini le tableau contenant l'ensemble des variables
        foreach ($this as $key => $value) {
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            if (method_exists($this, $method)) {
                switch ($key) {
                    case 'creation_datetime':
                    case 'percentage_remaining':
                    case 'number_guests':
                    case 'distance':
                    case 'number_participants':
                        break;
                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }
        $req->execute($array);
    }

    public function jsonSerialize()
    {
        $array = array();
        foreach ($this as $key => $value){
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            if (method_exists($this, $method)) {
                switch ($key){
                    case 'creator_latitude':
                    case 'creator_longitude':
                    case 'creation_datetime':
                    case 'with_localisation':
                    break;
                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }
        return $array;
    }


    public function resetObject(){
        foreach ($this as $key => $value) {
            // on convertie rend les noms de la base de données cohérent avec le nom de setters
            // ex: last_name devient LastName
            // met en majuscule la première lettre de tous les mots séparés par _
            $key = ucwords($key, "_");
            $key = preg_replace("#_#", "", $key);
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method(null);
            }
        }
    }


    public function deleteObject(){
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }

    /**
     * Retourne sous forme de tableau la liste des invités depuis une string séparée par des virgules
     * @param string $invited_friends_id
     * @param null|User $user
     * @return array|null
     */
    public static function getArrayParticipantsFromString(?string $invited_friends_id, ?User $user):?array {
        $invited_friends_id_array = explode(',', $invited_friends_id);
        // on supprime les doublons
        $invited_friends_id_array = array_unique($invited_friends_id_array);
        // s'il y a une virgule à la fin de la chaine de charactère
        // on enlève donc les valeurs vides du tableau
        $invited_friends_id_array = array_filter($invited_friends_id_array);
        // on supprime l'utilisateur lui même s'il est dans la liste
        if($user!=null) {
            if (($key = array_search($user->getId(), $invited_friends_id_array)) !== false) {
                unset($invited_friends_id_array[$key]);
            }
        }
        return $invited_friends_id_array;
    }

    public static function getHistoric(User $user, int $page=1):?array {
        // on fait un système de pagination
        $offset = ($page - 1) * NUMBER_EVENTS_BY_PAGE_HISTORIC;
        $req = Database::getDb()->prepare('SELECT *, ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance, events.id AS event_id, events_member_details.id AS detail_id FROM events_member_details INNER JOIN events ON events_member_details.event_id=events.id WHERE events_member_details.invited_friend_id=:user_id ORDER BY events_member_details.datetime DESC LIMIT ' . NUMBER_EVENTS_BY_PAGE_HISTORIC . ' OFFSET ' . $offset);
        $req->execute(array(
            "user_id" => $user->getId(),
            "user_longitude" => $user->getLongitude(),
            "user_latitude" => $user->getLatitude()
        ));
        $response = array();
        $results = $req->fetchAll();
        foreach ($results as $key => $result){

            // on supprime la première valeur qui rend le tableau ambigü
            array_splice($result, 0,1);
            // on crée l'évènement et le détail de l'utilisateur à propos de l'évènement
            // on renomme les clés des tableaux
            $event = new Event(Functions::replace_key($result, "event_id", "id"));
            $events_member_details = new EventMemberDetails(Functions::replace_key($result, "detail_id", "id"));
            $response[] = array("event" => $event, "event_member_details" => $events_member_details);
        }
        return $response;
    }


    /**
     * Recupère tous les évènements en cours pour l'utilisateur
     * @param int $limit
     * @return array|null
     */
    public static function getAllCurrentsEvents(int $limit=PHP_INT_MAX):?array {
        $req = Database::getDb()->prepare('SELECT *, ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance, events.id AS event_id, events_member_details.id AS detail_id FROM events_member_details INNER JOIN events ON events_member_details.event_id=events.id WHERE events_member_details.invited_friend_id=:user_id AND events.start_time<=NOW() AND events.end_time>=NOW() ORDER BY distance DESC LIMIT ' . $limit);
        $req->execute(array(
            "user_id" => Application::getUser()->getId(),
            "user_longitude" => Application::getUser()->getLongitude(),
            "user_latitude" => Application::getUser()->getLatitude()
        ));
        $response = array();
        $results = $req->fetchAll();
        foreach ($results as $key => $result){
            // on supprime la première valeur qui rend le tableau ambigü
            array_splice($result, 0,1);
            // on crée l'évènement et le détail de l'utilisateur à propos de l'évènement
            // on renomme les clés des tableaux
            $event = new Event(Functions::replace_key($result, "event_id", "id"));
            $events_member_details = new EventMemberDetails(Functions::replace_key($result, "detail_id", "id"));
            $response[] = array("event" => $event, "event_member_details" => $events_member_details);
        }
        return $response;
    }

    /**
     * @param bool $with_friend_relation
     * @return array|null
     * @throws PublicError
     */
    public function getAllGuests(bool $with_friend_relation=true):?array {
        if($this->is_authorized==null){
            $this->getFromDatabase();
        }
        if($this->is_authorized){
            $req = Database::getDb()->prepare('SELECT *, events_member_details.id AS detail_id, members.id AS member_id FROM events_member_details INNER JOIN members ON events_member_details.invited_friend_id = members.id WHERE event_id=:event_id');
            $req->execute(array('event_id' => $this->getId()));
            $response = array();
            $results = $req->fetchAll();
            foreach ($results as $key => $result){
                // on supprime la première valeur qui rend le tableau ambigü
                array_splice($result, 0,1);
                // on renomme les clés des tableaux
                $sub_array = array();
                $sub_array["friend"] = new Friend(Functions::replace_key($result, "member_id", "id"));
                if($with_friend_relation){
                    $sub_array["event_member_details"] = new EventMemberDetails(Functions::replace_key($result, "detail_id", "id"));
                }
                $response[] = $sub_array;
            }
            return $response;
        }
        else {
            return null;
        }
    }

    public function isOver():bool{
        $current_datetime = new \DateTime();
        $end_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndTime());
        if(($end_time_datetime->getTimestamp() - $current_datetime->getTimestamp()) < 0){
            return true;
        }
        else {
            return false;
        }
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
     * @throws PublicError
     */
    public function setId($id): void
    {
        if (!empty($id) && !preg_match('#^[0-9]+$#', $id)) {
            throw new PublicError("Invalid event_id format", ErrorCode::INVALID_EVENT_ID);
        }
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
    public function getCreatorId()
    {
        return $this->creator_id;
    }

    /**
     * @param mixed $creator_id
     * @throws PublicError
     */
    public function setCreatorId($creator_id): void
    {
        if (!empty($id) && !preg_match('#^[0-9]+$#', $id)) {
            throw new PublicError("Invalid creator_id format", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->creator_id = $creator_id;
    }

    /**
     * @return mixed
     */
    public function getCreatorLatitude()
    {
        return $this->creator_latitude;
    }

    /**
     * @param mixed $creator_latitude
     * @throws PublicError
     */
    public function setCreatorLatitude($creator_latitude): void
    {
        $regex = "#^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$#";
        if (!empty($latitude) && !preg_match($regex, $latitude)) {
            throw new PublicError("Creator latitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->creator_latitude = $creator_latitude;
    }

    /**
     * @return mixed
     */
    public function getCreatorLongitude()
    {
        return $this->creator_longitude;
    }

    /**
     * @param mixed $creator_longitude
     * @throws PublicError
     */
    public function setCreatorLongitude($creator_longitude): void
    {
        $regex = "#^\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$#";
        if (!empty($longitude) && !preg_match($regex, $longitude)) {
            throw new PublicError("Creator longitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->creator_longitude = $creator_longitude;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     * @throws PublicError
     */
    public function setPublic($public): void
    {
        if (!empty($public) && !preg_match('#^[0-1]$#', $public)) {
            throw new PublicError("Invalid public parameter value.", ErrorCode::INVALID_PUBLIC_VARIABLE);
        }
        $this->public = $public;
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
     * @throws PublicError
     */
    public function setLatitude($latitude): void
    {
        $regex = "#^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$#";
        if (!empty($latitude) && !preg_match($regex, $latitude)) {
            throw new PublicError("Latitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
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
     * @throws PublicError
     */
    public function setLongitude($longitude): void
    {
        $regex = "#^\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$#";
        if (!empty($longitude) && !preg_match($regex, $longitude)) {
            throw new PublicError("Longitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @param mixed $start_time
     * @throws PublicError
     */
    public function setStartTime($start_time): void
    {
        if (!empty($start_time) && !preg_match('#^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$#', $start_time)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->start_time = $start_time;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @param mixed $end_time
     * @throws PublicError
     */
    public function setEndTime($end_time): void
    {
        if (!empty($end_time) && !preg_match('#^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$#', $end_time)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->end_time = $end_time;
    }

    /**
     * @return mixed
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param mixed $information
     */
    public function setInformation($information): void
    {
        $this->information = $information;
    }

    /**
     * @return mixed
     */
    public function getPictureUrl()
    {
        return $this->picture_url;
    }

    /**
     * @param mixed $picture_url
     * @throws PublicError
     */
    public function setPictureUrl($picture_url): void
    {
        if (!empty($picture_url) && !preg_match('#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i', $picture_url)) {
            throw new PublicError("Invalid URL format.", ErrorCode::INVALID_URL);
        }
        $this->picture_url = $picture_url;
    }

    /**
     * @return mixed
     */
    public function getPercentageRemaining()
    {
        $current_datetime = new \DateTime();
        $start_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getStartTime());
        $end_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getEndTime());
        if ($current_datetime <= $start_datetime) {
            $pourcentage = 100;
        } elseif ($current_datetime >= $end_datetime) {
            $pourcentage = 0;
        } else {
            $pourcentage = 100 - round(100 * ($current_datetime->getTimestamp() - $start_datetime->getTimestamp()) / ($end_datetime->getTimestamp() - $start_datetime->getTimestamp()));
            $pourcentage = $pourcentage >= 0 && $pourcentage <= 100 ? $pourcentage : 0;
        }
        return $pourcentage;
    }

    /**
     * @return mixed
     */
    public function getNumberGuests()
    {
        $req_count             = Database::getDb()->prepare('SELECT COUNT(*) FROM events_member_details WHERE event_id=:event_id');
        $req_count->execute(array('event_id' => $this->getId()));
        return $req_count->fetch()[0];
    }

    /**
     * @return mixed
     */
    public function getNumberParticipants()
    {
        $req_count             = Database::getDb()->prepare('SELECT COUNT(*) FROM events_member_details WHERE event_id=:event_id AND response=1');
        $req_count->execute(array('event_id' => $this->getId()));
        return $req_count->fetch()[0];
    }

    /**
     * @return mixed
     */
    public function getCreationDatetime()
    {
        return $this->creation_datetime;
    }

    /**
     * @param mixed $creation_datetime
     * @throws PublicError
     */
    public function setCreationDatetime($creation_datetime): void
    {
        if (!empty($creation_datetime) && !preg_match('#^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$#', $creation_datetime)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->creation_datetime = $creation_datetime;
    }

    /**
     * @return mixed
     */
    public function getWithLocalisation()
    {
        return $this->with_localisation;
    }

    /**
     * @param mixed $with_localisation
     * @throws PublicError
     */
    public function setWithLocalisation($with_localisation): void
    {
        if (!empty($with_localisation) && !is_bool($with_localisation) && !preg_match("#^0|1$#", $with_localisation)) {
            throw new PublicError("With_localisation parameter invalid value.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->with_localisation = $with_localisation;
    }

    /**
     * @return float
     */
    public function getDistance(): ?float
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     */
    public function setDistance(?float $distance): void
    {
        $this->distance = $distance;
    }




}
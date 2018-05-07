<?php
namespace ZONNY\Models\Events;

use ZONNY\Utils\Database;
use ZONNY\Utils\DatetimeISO8601;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

/**
 * @SWG\Definition(
 *   definition="EventMemberDetails",
 *   type="object",
 *    required={"id", "name", "creator_id", "latitude", "longitude", "start_time", "end_time", "percentage_remaining", "number_guests", "number_participants"}
 * )
 */
class EventMemberDetails implements \JsonSerializable
{

    public const HAS_NOT_ANSWERERD = 0;
    public const HE_COMES = 1;
    public const HE_DOESNT_COME = 2;

    public const IS_NOT_CREATOR = 0;
    public const IS_CREATOR = 1;

    /**
     * @var integer
     * @SWG\Property(
     *     description="The primary id",
     *     example=632
     * )
     */
    private $id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The id of the concerning event",
     *     example=13649
     * )
     */
    private $event_id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The id of the friend in question",
     *     example=123
     * )
     */
    private $invited_friend_id;
    private $friend_latitude;
    private $friend_longitude;
    /**
     * @var integer
     * @SWG\Property(
     *     description="Response of the concerning friend. 0 the friend has not answered yet. 1 the friend has answered he comes. 2 the friend doesn't come",
     *     enum={0, 1, 2}
     * )
     */
    private $response;
    /**
     * @var integer
     * @SWG\Property(
     *     description="If the friend is the creator of the event or not. 1 is true. 0 is false",
     *     enum={0, 1}
     * )
     */
    private $creator;
    private $datetime;

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
     * Récupère les informations concernant l'objet à partir de son id ou key_app
     */
    public function getFromDatabase():bool
    {
        $req = Database::getDb()->prepare('SELECT * from events_member_details WHERE id=:id OR (invited_friend_id=:invited_friend_id AND event_id=:event_id)');
        $req->execute(array(
            "id" => $this->getId(),
            "invited_friend_id" => $this->getInvitedFriendId(),
            "event_id" => $this->getEventId()
        ));
        $data = $req->fetch();
        if($data!=false){
            $this->hydrate(($data));
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @throws PublicError
     */
    public function addToDataBase()
    {
        $req = Database::getDb()->prepare("INSERT INTO events_member_details (event_id, invited_friend_id, friend_latitude, friend_longitude, response, creator, datetime) VALUES (:event_id, :invited_friend_id, :friend_latitude, :friend_longitude, :response, :creator, :datetime)");

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
                        break;
                    case 'datetime':
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
        $req = Database::getDb()->prepare('DELETE from events_member_details WHERE id=?');
        $req->execute(array($this->getId()));
    }

    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE events_member_details SET event_id = COALESCE(:event_id, event_id), invited_friend_id = COALESCE(:invited_friend_id, invited_friend_id), friend_latitude = COALESCE(:friend_latitude, friend_latitude), friend_longitude = COALESCE(:friend_longitude, friend_longitude), response = COALESCE(:response, response), creator = COALESCE(:creator, creator) WHERE id = :id');

        $array = array();
        // on défini le tableau contenant l'ensemble des variables
        foreach ($this as $key => $value) {
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            if (method_exists($this, $method)) {
                switch ($key) {
                    case 'datetime':
                        break;
                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }
        $req->execute($array);
    }

    /**
     * @return array|mixed
     */
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
                    case 'friend_latitude':
                    case 'friend_longitude':
                    case 'datetime':
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id??null;
    }

    /**
     * @param mixed $id
     * @throws PublicError
     */
    public function setId($id): void
    {
        if (!empty($id) && !preg_match('#^[0-9]+$#', $id)) {
            throw new PublicError("Invalid id format", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id??null;
    }

    /**
     * @param mixed $event_id
     * @throws PublicError
     */
    public function setEventId($event_id): void
    {
        if (!empty($event_id) && !preg_match('#^[0-9]+$#', $event_id)) {
            throw new PublicError("Invalid event_id format", ErrorCode::INVALID_EVENT_ID);
        }
        $this->event_id = $event_id;
    }

    /**
     * @return mixed
     */
    public function getInvitedFriendId()
    {
        return $this->invited_friend_id??null;
    }

    /**
     * @param mixed $invited_friend_id
     * @throws PublicError
     */
    public function setInvitedFriendId($invited_friend_id): void
    {
        if (!empty($invited_friend_id) && !preg_match('#^[0-9]+$#', $invited_friend_id)) {
            throw new PublicError("Invalid friend_id format", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->invited_friend_id = $invited_friend_id;
    }

    /**
     * @return mixed
     */
    public function getFriendLatitude()
    {
        return $this->friend_latitude??null;
    }

    /**
     * @param mixed $friend_latitude
     * @throws PublicError
     */
    public function setFriendLatitude($friend_latitude): void
    {
        $regex = "#^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$#";
        if (!empty($friend_latitude) && !preg_match($regex, $friend_latitude)) {
            throw new PublicError("Latitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->friend_latitude = $friend_latitude;
    }

    /**
     * @return mixed
     */
    public function getFriendLongitude()
    {
        return $this->friend_longitude??null;
    }

    /**
     * @param mixed $friend_longitude
     * @throws PublicError
     */
    public function setFriendLongitude($friend_longitude): void
    {
        $regex = "#^\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$#";
        if (!empty($friend_longitude) && !preg_match($regex, $friend_longitude)) {
            throw new PublicError("Longitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->friend_longitude = $friend_longitude;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response??null;
    }

    /**
     * @param mixed $response
     * @throws PublicError
     */
    public function setResponse($response): void
    {
        if (!empty($response) && !preg_match('#^[0-1-2]$#', $response)) {
            throw new PublicError("Invalid response parameter value.", ErrorCode::INVALID_PUBLIC_VARIABLE);
        }
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator??null;
    }

    /**
     * @param mixed $creator
     * @throws PublicError
     */
    public function setCreator($creator): void
    {
        if (!empty($creator) && !is_bool($creator) && !preg_match("#^0|1$#", $creator)) {
            throw new PublicError("is_creator parameter invalid value.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime!=null ? new DatetimeISO8601($this->datetime): null;
    }

    /**
     * @param mixed $datetime
     * @throws PublicError
     */
    public function setDatetime($datetime): void
    {
        if (!empty($datetime) && !preg_match('#^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$#', $datetime)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->datetime = $datetime;
    }



}
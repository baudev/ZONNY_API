<?php
namespace ZONNY\Models\Events;

use ZONNY\Models\Accounts\Friend;
use ZONNY\Utils\Database;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

/**
 * @SWG\Definition(
 *   definition="EventRequest",
 *   type="object",
 *    required={"id", "event_id", "friend_id", "response"}
 * )
 */
class EventRequest implements \JsonSerializable
{

    const HAS_NOT_ANSWERED_YET = 0;
    const HAS_RESPONDED_TRUE = 1;
    const HAS_RESPONDED_FALSE = 2;

    /**
     * @var integer
     * @SWG\Property(
     *     description="The primary id",
     *     example=13649
     * )
     */
    private $id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The id of the concerning event",
     *     example=4899
     * )
     */
    private $event_id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The id of the concerning friend who wants to come to this event",
     *     example=1894
     * )
     */
    private $friend_id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The response of the creator concerning this request. 0 the creator has not respond yet. 1 the creator has invited his friend. 2 the creator ignored this request.",
     *     example=1
     * )
     */
    private $response;
    private $creation_datetime;

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
        $req = Database::getDb()->prepare('SELECT * from events_requests WHERE id=:id OR (event_id=:event_id AND friend_id=:friend_id)');
        $req->execute(array(
            "id" => $this->getId(),
            "event_id" => $this->getEventId(),
            "friend_id" => $this->getFriendId()
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
        $req = Database::getDb()->prepare("INSERT INTO events_requests (event_id, friend_id, response, creation_datetime) VALUES (:event_id, :friend_id, :response, :creation_datetime)");

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
        $req = Database::getDb()->prepare('DELETE from events_requests WHERE id=?');
        $req->execute(array($this->getId()));
    }

    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE events_requests SET event_id = COALESCE(:event_id, event_id), friend_id = COALESCE(:friend_id, friend_id), response = COALESCE(:response, response) WHERE id = :id');

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
                    case 'creation_datetime':
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

    /**
     * @param int $page
     * @param bool $with_friend_information
     * @return array|null
     * @throws \ZONNY\Utils\PublicError
     */
    public function getAllEventRequests(int $page=1, bool $with_friend_information):?array {
        $offset = ($page - 1) * NUMBER_REQUEST_BY_PAGE_EVENT;
        $req = Database::getDb()->prepare('SELECT * from events_requests WHERE event_id=:event_id LIMIT '.NUMBER_REQUEST_BY_PAGE_EVENT.' OFFSET '.$offset);
        $req->execute(array(
            "event_id" => $this->getEventId()
        ));
        $data = $req->fetchAll();
        if(!empty($data)){
            $response = array();
            foreach ($data as $request){
                $sub_array = array();
                $request = new EventRequest($request);
                $sub_array['request'] = $request;
                if($with_friend_information){
                    $friend = new Friend();
                    $friend->setId($request->getFriendId());
                    if($friend->getFromDatabase()){
                        $sub_array['friend'] = $friend;
                    }
                    else {
                        $sub_array['friend'] = array();
                    }
                }
                $response[] = $sub_array;
            }
            return $response;
        }
        else {
            return array();
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
        return $this->id;
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
        return $this->event_id;
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
    public function getFriendId()
    {
        return $this->friend_id;
    }

    /**
     * @param mixed $friend_id
     * @throws PublicError
     */
    public function setFriendId($friend_id): void
    {
        if (!empty($friend_id) && !preg_match('#^[0-9]+$#', $friend_id)) {
            throw new PublicError("Invalid friend_id format", ErrorCode::INVALID_EVENT_ID);
        }
        $this->friend_id = $friend_id;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
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
        if (!empty($creation_datetime) && !preg_match('#^([2][01]|[1][6-9])\d{2}\-([0]\d|[1][0-2])\-([0-2]\d|[3][0-1])(\s([0-1]\d|[2][0-3])(\:[0-5]\d){1,2})?$#', $creation_datetime)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->creation_datetime = $creation_datetime;
    }



}
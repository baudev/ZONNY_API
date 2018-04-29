<?php
namespace ZONNY\Models\Accounts;

use ZONNY\Utils\ApiKey;
use ZONNY\Utils\Application;
use ZONNY\Utils\Database;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\Functions;
use ZONNY\Utils\PublicError;
class User implements \JsonSerializable
{
    private $_id;
    private $_key_app;
    private $_fb_access_token;
    private $_fb_user_id;
    private $_expire;
    private $_name;
    private $_first_name;
    private $_last_name;
    private $_profile_picture_url;
    private $_latitude;
    private $_longitude;
    private $_last_latitude;
    private $_last_longitude;
    private $_level;
    private $_unavailable;
    private $_gcm_registration_id;
    private $_location_last_check_up;
    private $_creation_datetime;
    private $_last_add_events;
    private $_last_add_events_google;

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
        $req = Database::getDb()->prepare('SELECT * from members WHERE id=:id OR key_app=:key_app OR fb_user_id=:fb_user_id');
        $req->execute(array(
            "id" => $this->getId(),
            "key_app" => $this->getKeyApp(),
            "fb_user_id" => $this->getFbUserId()
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
        $this->setKeyApp(ApiKey::generateApiKey());
        $req = Database::getDb()->prepare("INSERT INTO members (key_app, fb_access_token, fb_user_id, expire, name, first_name, last_name, profile_picture_url, latitude, longitude, last_latitude, last_longitude, unavailable, gcm_registration_id, location_last_check_up, creation_datetime, last_add_events, last_add_events_google) VALUES (:key_app, :fb_access_token, :fb_user_id, :expire, :name, :first_name, :last_name, :profile_picture_url, :latitude, :longitude, :last_latitude, :last_longitude, :unavailable, :gcm_registration_id, :location_last_check_up, :creation_datetime, :last_add_events, :last_add_events_google)");

        $array = array();
        // on défini le tableau contenant l'ensemble des variables
        foreach ($this as $key => $value) {
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            // on enlève le premier underscore de la variable
            $key = substr_replace($key, "", 0, 1);
            if (method_exists($this, $method)) {
                switch ($key) {
                    case 'id':
                    case 'level':
                        break;
                    case 'latitude':
                    case 'longitude':
                        $array[$key] = $this->$method() + Functions::randomFloat() * cos($this->$method()) * 0.005;
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
        $req = Database::getDb()->prepare('DELETE from members WHERE id=?');
        $req->execute(array($this->getId()));
    }

    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE members SET key_app = COALESCE(:key_app, key_app), fb_access_token = COALESCE(:fb_access_token, fb_access_token), fb_user_id = COALESCE(:fb_user_id, fb_user_id), expire = COALESCE(:expire, expire), name = COALESCE(:name, name), first_name = COALESCE(:first_name, first_name), last_name = COALESCE(:last_name, last_name), profile_picture_url = COALESCE(:profile_picture_url, profile_picture_url), latitude = COALESCE(:latitude, latitude), longitude = COALESCE(:longitude, longitude), last_latitude = COALESCE(:last_latitude, last_latitude), last_longitude = COALESCE(:last_longitude, last_longitude), unavailable=COALESCE(:unavailable, unavailable), gcm_registration_id = COALESCE(:gcm_registration_id, gcm_registration_id), location_last_check_up = COALESCE(:location_last_check_up, location_last_check_up), last_add_events = COALESCE(:last_add_events, last_add_events), last_add_events_google = COALESCE(:last_add_events_google, last_add_events_google) WHERE id = :id');

        $array = array();
        // on défini le tableau contenant l'ensemble des variables
        foreach ($this as $key => $value) {
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            // on enlève le premier underscore de la variable
            $key = substr_replace($key, "", 0, 1);
            if (method_exists($this, $method)) {
                switch ($key) {
                    case 'creation_datetime':
                    case 'level':
                        break;
                    case 'latitude':
                    case 'longitude':
                        $array[$key] = $this->$method() + Functions::randomFloat() * cos($this->$method()) * 0.005;
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
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $array = array();
        foreach ($this as $key => $value){
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            // on enlève le premier underscore de la variable
            $key = substr_replace($key, "", 0, 1);
            if (method_exists($this, $method)) {
                switch ($key){
                    case 'fb_access_token':
                    case 'fb_user_id':
                    case 'last_latitude':
                    case 'last_longitude':
                    case 'gcm_registration_id':
                    case 'creation_datetime':
                    case 'last_add_events':
                    case 'last_add_events_google':
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
     * Calcule le level de l'utilisateur
     * @return mixed
     */
    public function getLevel()
    {
        $req = Database::getDb()->prepare("SELECT * FROM events_member_details WHERE invited_friend_id=? AND datetime >= NOW() - interval '7 day'");
        $req->execute(array($this->getId()));
        $compteur = 0;
        $infos    = $req->fetchAll();
        foreach ($infos as $key => $info) {
            if ($info['creator']) {
                $compteur = $compteur + 6;
            } elseif ($info['response'] == 1) {
                $compteur = $compteur + 4;
            } else {
                $compteur = $compteur + 2;
            }
        }

        // si le compteur est inférieur à 10 alors on vérifie s'il s'agit d'un nouvel utilisateur de moins de 7 jours
        if ($compteur <= 10) {
            $req_info = Database::getDb()->prepare('SELECT * FROM members WHERE id=?');
            $req_info->execute(array($this->getId()));
            $friend_infos = $req_info->fetch();
            if (strtotime(date('Y-m-d H:i:s')) - strtotime($friend_infos['creation_datetime']) <= 604800) {
                $compteur = 10;
            }
        }
        return $compteur;
    }

    /**
     * Retourne si l'utilisateur doit mettre à jour sa position ou non
     * @return bool
     */
    public function needUpdateLocalisation():bool {
        // calcule la différence entre le datetime actuel et celui de la dernière localisation
        $current = new \DateTime();
        $last_localisation = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getLocationLastCheckUp());
        $diff = $current->getTimestamp() - $last_localisation->getTimestamp();
        if($diff->s>NUMBER_SECONDS_MUST_RESEND_LOCATION){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Retourne si l'utilisateur est occupé ou non (mode fantôme)
     */
    public function isUnavailable(){
        if(!empty($this->getUnavailable())) {
            $current = new \DateTime();
            $unavailable = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getUnavailable());
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
     * Vérifie si l'utilisateur est actuellement a un évènement (évènement en cours auquel il a répondu oui)
     * @return bool
     */
    public function isCurrentlyToAnEvent():bool {
        $unavailable_req = Database::getDb()->prepare('SELECT count(*) FROM events_member_details INNER JOIN events ON events_member_details.event_id=events.id WHERE events.start_datetime<=NOW() AND events.duration>=NOW() AND invited_friend_id=:friend_id AND response=1');
        $unavailable_req->execute(array(
            "friend_id" => $this->getId(),
        ));
        $unavailable_req->fetch()[0] == 0 ? false : true;
    }

    /**
     * Retourne la liste de tous les amis de l'utilisateur
     * @param bool $with_friend_relation
     * @return array
     */
    public function getAllHisFriends(bool $with_friend_relation=true):?array {
        $req = Database::getDb()->prepare('SELECT *, members.id as id_member, friends_links.id as id_relation, ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance FROM members INNER JOIN friends_links ON friends_links.friend_id=members.id WHERE friends_links.user_id=:user_id ORDER BY friends_links.mutual_friends DESC');
        $req->execute(array(
            "user_id" => $this->getId(),
            "user_longitude" => Application::getUser()->getLongitude(),
            "user_latitude" => Application::getUser()->getLatitude()
        ));
        $response = array();
        while ($infos = $req->fetch()) {
            // on supprime les deux premières valeurs qui rendent le tableau ambigü
            array_splice($infos, 0,2);
            // on crée l'utilisateur et la relation d'amitié associée
            // on renomme les clés des tableaux
            $sub_array["friend"] = new Friend(Functions::replace_key($infos, "id_member", "id"));
            if($with_friend_relation) {
                $sub_array["friend_link"] = new FriendLink(Functions::replace_key($infos, "id_relation", "id"));
            }
            $response[] = $sub_array;
        }
        return $response;
    }

    /**
     * @return int|null
     * @throws PublicError
     */
    public function getNumberCommunFriends():?int {
        // on récupère la liste de tous les amis de l'utilisateur
        $user_friends = Application::getUser()->getAllHisFriends(false);
        $response = 0;
        foreach ($user_friends as $key => $friend){
            // on regarde les relations d'amitiés
            $friend_relation = new FriendLink();
            $friend_relation->setUserId($this->getId());
            // on convertit le tableau en object Friend
            /** @var  $friend Friend */
            $friend = (object)$friend['friend'];
            $friend_relation->setFriendId($friend->getId());
            if($friend_relation->getFromDatabase()){
                // la relation existe
                $response++;
            }
        }
        return $response;
    }

    public function has_Not_Created_Recents_Google_Places(){
        if($this->getLastAddEventsGoogle()==null){
            return true;
        }
        $current_date = new \DateTime();
        $last_google_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getLastAddEventsGoogle());
        if($current_date->getTimestamp() - $last_google_datetime->getTimestamp() > MIN_INTERVAL_GOOGLE_PLACES_RESEARCH){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Getters and Setters
     */

    /**
     * @return mixed
     */
    public function getId():?int
    {
        return $this->_id??null;
    }

    /**
     * @param mixed $id
     * @throws PublicError
     */
    public function setId($id): void
    {
        if (!empty($id) && !preg_match('#^[0-9]+$#', $id)) {
            throw new PublicError("Id invalid format.", ErrorCode::INVALID_FRIEND_ID);
        }
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getKeyApp()
    {
        return $this->_key_app??null;
    }

    /**
     * @param mixed $key_app
     * @throws PublicError
     */
    public function setKeyApp($key_app): void
    {
        if (!empty($key_app) && !preg_match('#\w{100}$#', $key_app)) {
            throw new PublicError("Key_app invalid format", ErrorCode::INVALID_KEY_APP);
        }
        $this->_key_app = $key_app;
    }

    /**
     * @return mixed
     */
    public function getFbAccessToken()
    {
        return $this->_fb_access_token??null;
    }


    /**
     * Getters and Setters
     */

    /**
     * @param mixed $fb_access_token
     * @throws PublicError
     */
    public function setFbAccessToken($fb_access_token): void
    {
        if (!empty($fb_access_token) && !preg_match('#^[a-z0-9A-Z]+$#', $fb_access_token)) {
            throw new PublicError("fb_access_token invalid format.", ErrorCode::INVALID_FB_ACCESS_TOKEN);
        }
        $this->_fb_access_token = $fb_access_token;
    }

    /**
     * @return mixed
     */
    public function getFbUserId()
    {
        return $this->_fb_user_id??null;
    }

    /**
     * @param mixed $fb_user_id
     */
    public function setFbUserId($fb_user_id): void
    {
        $this->_fb_user_id = $fb_user_id;
    }

    /**
     * @return mixed
     */
    public function getExpire()
    {
        return $this->_expire??null;
    }

    /**
     * @param mixed $expire
     * @throws PublicError
     */
    public function setExpire($expire): void
    {
        if (!empty($expire) && !preg_match('#^([2][01]|[1][6-9])\d{2}\-([0]\d|[1][0-2])\-([0-2]\d|[3][0-1])(\s([0-1]\d|[2][0-3])(\:[0-5]\d){1,2})?$#', $expire)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->_expire = $expire;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name??null;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->_first_name??null;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name): void
    {
        $this->_first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->_last_name??null;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name): void
    {
        $this->_last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getProfilePictureUrl()
    {
        return $this->_profile_picture_url??null;
    }

    /**
     * @param mixed $profile_picture_url
     * @throws PublicError
     */
    public function setProfilePictureUrl($profile_picture_url): void
    {
        if (!empty($profile_picture_url) && !preg_match('#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i', $profile_picture_url)) {
            throw new PublicError("Invalid URL format.", ErrorCode::INVALID_URL);
        }
        $this->_profile_picture_url = $profile_picture_url;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->_latitude??null;
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
        if(!empty($latitude)) {
            // on met à jour l'ancienne latitude
            $this->setLastLatitude($this->getLatitude());
            // on met à jour la dernière date d'update de la localisation
            $current_date = new \DateTime();
            $this->setLocationLastCheckUp($current_date->format('Y-m-d H:i:s'));
            // on met à jour la latitude
            $this->_latitude = $latitude;
        }
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->_longitude??null;
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
        if(!empty($longitude)) {
            // on met à jour l'ancienne longitude
            $this->setLastLongitude($this->getLongitude());
            // on met à jour la dernière date d'update de la localisation
            $current_date = new \DateTime();
            $this->setLocationLastCheckUp($current_date->format('Y-m-d H:i:s'));
            // on met à jour la longitude
            $this->_longitude = $longitude;
        }
    }

    /**
     * @return mixed
     */
    public function getLastLatitude()
    {
        return $this->_last_latitude??null;
    }

    /**
     * @param mixed $last_latitude
     * @throws PublicError
     */
    public function setLastLatitude($last_latitude): void
    {
        $regex = "#^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$#";
        if (!empty($last_latitude) && !preg_match($regex, $last_latitude)) {
            throw new PublicError("Latitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->_last_latitude = $last_latitude;
    }

    /**
     * @return mixed
     */
    public function getLastLongitude()
    {
        return $this->_last_longitude??null;
    }

    /**
     * @param mixed $last_longitude
     * @throws PublicError
     */
    public function setLastLongitude($last_longitude): void
    {
        $regex = "#^\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$#";
        if (!empty($last_longitude) && !preg_match($regex, $last_longitude)) {
            throw new PublicError("Longitude invalid format.", ErrorCode::INVALID_GPS_LOCATION);
        }
        $this->_last_longitude = $last_longitude;
    }

    /**
     * @return mixed
     */
    public function getUnavailable()
    {
        return $this->_unavailable??null;
    }

    /**
     * @param mixed $unavailable
     * @throws PublicError
     */
    public function setUnavailable($unavailable): void
    {
        if (!empty($unavailable) && !preg_match('#^([2][01]|[1][6-9])\d{2}\-([0]\d|[1][0-2])\-([0-2]\d|[3][0-1])(\s([0-1]\d|[2][0-3])(\:[0-5]\d){1,2})?$#', $unavailable)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->_unavailable = $unavailable;
    }

    /**
     * @return mixed
     */
    public function getLocationLastCheckUp()
    {
        return $this->_location_last_check_up??null;
    }

    /**
     * @param mixed $location_last_check_up
     * @throws PublicError
     */
    public function setLocationLastCheckUp($location_last_check_up): void
    {
        if (!empty($location_last_check_up) && !preg_match('#^([2][01]|[1][6-9])\d{2}\-([0]\d|[1][0-2])\-([0-2]\d|[3][0-1])(\s([0-1]\d|[2][0-3])(\:[0-5]\d){1,2})?$#', $location_last_check_up)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->_location_last_check_up = $location_last_check_up;
    }

    /**
     * @return mixed
     */
    public function getGcmRegistrationId()
    {
        return $this->_gcm_registration_id??null;
    }

    /**
     * @param mixed $gcm_registration_id
     * @throws PublicError
     */
    public function setGcmRegistrationId($gcm_registration_id): void
    {
        if (!empty($gcm_registration_id) && !preg_match('#^[a-z0-9A-Z:_-]+$#', $gcm_registration_id)) {
            throw new PublicError("gcm_id invalid format.", ErrorCode::GCM_INVALID_FORMAT);
        }
        $this->_gcm_registration_id = $gcm_registration_id;
    }

    /**
     * @return mixed
     */
    public function getCreationDatetime()
    {
        return $this->_creation_datetime??null;
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
        $this->_creation_datetime = $creation_datetime;
    }

    /**
     * @return mixed
     */
    public function getLastAddEvents()
    {
        return $this->_last_add_events??null;
    }

    /**
     * @param mixed $last_add_events
     * @throws PublicError
     */
    public function setLastAddEvents($last_add_events): void
    {
        if (!empty($last_add_events) && !preg_match('#^([2][01]|[1][6-9])\d{2}\-([0]\d|[1][0-2])\-([0-2]\d|[3][0-1])(\s([0-1]\d|[2][0-3])(\:[0-5]\d){1,2})?$#', $last_add_events)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->_last_add_events = $last_add_events;
    }

    /**
     * @return mixed
     */
    public function getLastAddEventsGoogle()
    {
        return $this->_last_add_events_google??null;
    }

    /**
     * @param mixed $last_add_events_google
     * @throws PublicError
     */
    public function setLastAddEventsGoogle($last_add_events_google): void
    {
        if (!empty($last_add_events_google) && !preg_match('#^([2][01]|[1][6-9])\d{2}\-([0]\d|[1][0-2])\-([0-2]\d|[3][0-1])(\s([0-1]\d|[2][0-3])(\:[0-5]\d){1,2})?$#', $last_add_events_google)) {
            throw new PublicError("Datetime format invalid. Ex: 2017-09-13 13:35:59", ErrorCode::INVALID_DATETIME);
        }
        $this->_last_add_events_google = $last_add_events_google;
    }



}

// Ajoute la définition de User
/**
 *  @SWG\Definition(
 *   definition="User",
 *   type="object",
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/GoodFriend"),
 *       @SWG\Schema(
 *           required={"_key_app"},
 *           @SWG\Property(property="_key_app", type="string", description="The API KEY"),
 *           @SWG\Property(property="_expire", format="datetime", type="number", description="The expiration datetime of the Facebook Token", example="2018-04-02 12:12:00"),
 *           @SWG\Property(property="_unavailable", format="datetime", type="number", description="The datetime until the user is unavailable", example="2018-03-26 23:52:45"),
 *           @SWG\Property(property="_location_last_ckeck_up", format="datetime", type="number", description="The datetime when the user sent his last known position", example="2018-04-01 22:56:10"),
 *       )
 *   }
 * )
 */
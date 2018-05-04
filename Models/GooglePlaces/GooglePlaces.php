<?php

namespace ZONNY\Models\GooglePlaces;

use DateTime;
use DateTimeZone;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;
use SKAgarwal\GoogleApi\PlacesApi;
use ZONNY\Models\Accounts\User;
use ZONNY\Utils\Application;
use ZONNY\Utils\Database;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PrivateError;

/**
 * @SWG\Definition(
 *   definition="PublicPlace",
 *   type="object",
 *    required={"id", "latitude", "longitude", "start_time", "end_time", "percentage_remaining", "distance"}
 * )
 */
class GooglePlaces implements \JsonSerializable
{

    /**
     * @var integer
     * @SWG\Property(
     *     description="The primary id",
     *     example=13697
     * )
     */
    private $id;
    private $google_id;
    /**
     * @var string
     * @SWG\Property(
     *     description="The name of the place",
     *     example="The fabric of wheelbarrow"
     * )
     */
    private $name;
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
     * @var string
     * @SWG\Property(
     *     description="URL of the event's picture",
     *     example="https://images.pexels.com/photos/681847/pexels-photo-681847.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260"
     * )
     */
    private $picture_url;
    private $category;
    private $day_0_open;
    private $day_1_open;
    private $day_2_open;
    private $day_3_open;
    private $day_4_open;
    private $day_5_open;
    private $day_6_open;
    private $day_0_close;
    private $day_1_close;
    private $day_2_close;
    private $day_3_close;
    private $day_4_close;
    private $day_5_close;
    private $day_6_close;
    private $day_0_invert;
    private $day_1_invert;
    private $day_2_invert;
    private $day_3_invert;
    private $day_4_invert;
    private $day_5_invert;
    private $day_6_invert;
    private $json;
    /**
     * @var float
     * @SWG\Property(
     *     description="The mark of the place. Min 1. Max 5",
     *     example=4.3
     * )
     */
    private $review;

    /**
     * @var datetime
     * @SWG\Property(
     *     description="When the place opens.",
     *     example="2018-04-02 08:00:00"
     * )
     */
    private $start_time;
    /**
     * @var datetime
     * @SWG\Property(
     *     description="When the place closes.",
     *     example="2018-04-02 20:00:00"
     * )
     */
    private $end_time;
    /**
     * @var integer
     * @SWG\Property(
     *     description="Actual percentage remaining of the total duration of the event",
     *     example=94
     * )
     */
    private $percentage_remaining;
    /**
     * @var float
     * @SWG\Property(
     *     description="Distance between friend and user",
     *     example=213.152
     * )
     */
    private $distance;


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
        $req = Database::getDb()->prepare('SELECT *, ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance from google_places WHERE id=:id OR google_id=:google_id');
        $req->execute(array(
            "id" => $this->getId(),
            "google_id" => $this->getGoogleId(),
            "user_longitude" => Application::getUser()->getLongitude(),
            "user_latitude" => Application::getUser()->getLatitude()
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
     *
     */
    public function addToDataBase()
    {
        $req = Database::getDb()->prepare("INSERT INTO google_places (google_id, name, latitude, longitude, picture_url, category, day_0_open, day_1_open, day_2_open, day_3_open, day_4_open, day_5_open, day_6_open, day_0_close, day_1_close, day_2_close, day_3_close, day_4_close, day_5_close, day_6_close, day_0_invert, day_1_invert, day_2_invert, day_3_invert, day_4_invert, day_5_invert, day_6_invert, creation_datetime, json, review) VALUES (:google_id, :name, :latitude, :longitude, :picture_url, :category, :day_0_open, :day_1_open, :day_2_open, :day_3_open, :day_4_open, :day_5_open, :day_6_open, :day_0_close, :day_1_close, :day_2_close, :day_3_close, :day_4_close,:day_5_close, :day_6_close, :day_0_invert, :day_1_invert, :day_2_invert, :day_3_invert, :day_4_invert,:day_5_invert, :day_6_invert, NOW(), :json, :review)");

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
                    case 'distance':
                        break;
                    case 'category':
                        $array[$key] = implode(",", $this->$method());
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
        // on insère l'id de la place
        $this->setId(Database::getDb()->lastInsertId());

        // on insère dans la table google_places_assoc les categories correspondantes à l'évènement
        foreach ($this->getCategory() as $category){
            // à partir du nom on récupère le numéro de la catégorie ZONNY
            $google_category = new GooglePlacesCategory();
            $google_category->setName($category);
            if($google_category->getFromDatabase()){
                // la catégorie a été trouvé
                // on crée donc une catégorie pour l'évènement
                $event_category = new GooglePlacesAssociativeCategory();
                $event_category->setCatId($google_category->getCatId());
                $event_category->setPlaceId($this->getId());
                $event_category->addToDataBase();
            }
            else {
                // la catégorie n'a pas été trouvé, on le note dans les erreurs pour être traité par l'équipe
                $erreur = new PrivateError("Impossible de trouver la catégorie correspondante à celle de Google : ".$category, ErrorCode::UNKNOWN_TYPE);
                $erreur->log_error(Application::getUser());
            }
        }
    }

    public function deleteFromDataBase()
    {
        $req = Database::getDb()->prepare('DELETE from google_places WHERE id=?');
        $req->execute(array($this->getId()));
    }

    /**
     * ATTENTION IL S'AGIT D'UNE MODIFICATION BRUTALE SANS COALESCE
     */
    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE google_places SET name=:name, latitude=:latitude, longitude=:longitude, picture_url=:picture_url, category=:category, day_0_open=:day_0_open, day_1_open=:day_1_open, day_2_open=:day_2_open, day_3_open=:day_3_open, day_4_open=:day_4_open, day_5_open=:day_5_open, day_6_open=:day_6_open, day_0_close=:day_0_close, day_1_close=:day_1_close, day_2_close=:day_2_close, day_3_close=:day_3_close, day_4_close=:day_4_close, day_5_close=:day_5_close, day_6_close=:day_6_close, day_0_invert=:day_0_invert, day_1_invert=:day_1_invert, day_2_invert=:day_2_invert, day_3_invert=:day_3_invert, day_4_invert=:day_4_invert, day_5_invert=:day_5_invert, day_6_invert=:day_6_invert, json=:json, review=:review  WHERE google_id=:google_id OR id=:id');

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
                    case 'distance':
                        break;
                    case 'category':
                        $array[$key] = implode(",", $this->$method());
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
                    case 'google_id':
                    case 'creation_datetime':
                    case 'category':
                    case 'day_0_open':
                    case 'day_1_open':
                    case 'day_2_open':
                    case 'day_3_open':
                    case 'day_4_open':
                    case 'day_5_open':
                    case 'day_6_open':
                    case 'day_0_close':
                    case 'day_1_close':
                    case 'day_2_close':
                    case 'day_3_close':
                    case 'day_4_close':
                    case 'day_5_close':
                    case 'day_6_close':
                    case 'day_0_invert':
                    case 'day_1_invert':
                    case 'day_2_invert':
                    case 'day_3_invert':
                    case 'day_4_invert':
                    case 'day_5_invert':
                    case 'day_6_invert':
                    case 'json':
                        break;
                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }
        // on ajoute les horaires et le pourcentage restant
        $this->get_current_opening_hours();
        $array['start_time'] = $this->start_time;
        $array['end_time'] = $this->end_time;
        $array['pourcentage_remaining'] = $this->percentage_remaining;
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
     * @param int $limit
     * @return GooglePlaces[]|null
     */
    public static function getNearPlaces(int $limit=PHP_INT_MAX):?array{
        $response = array();
        $req = Database::getDb()->prepare('SELECT *, ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance from google_places ORDER BY distance LIMIT '.$limit);
        $req->execute(array(
                "user_longitude" => Application::getUser()->getLongitude(),
                "user_latitude" => Application::getUser()->getLatitude()
        ));
        $data = $req->fetchAll();
        foreach ($data as $google_place){
            $response[] = new GooglePlaces($google_place);
        }
        return $response;
    }

    /**
     * @return bool
     */
    public function isOver():bool{
        if($this->end_time==null){
            $this->get_current_opening_hours();
        }
        if($this->end_time!=null) {
            $current_datetime = new \DateTime();
            $end_time_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->end_time);
            if (($end_time_datetime->getTimestamp() - $current_datetime->getTimestamp()) < 0) {
                return true;
            } else {
                return false;
            }
        }
        else {
            return false;
        }
    }


    /**
     * @param $latitude
     * @param $longitude
     * @param $category_search
     * @param int $radius_meters
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public static function getPlacesFromGoogleDatabase($latitude, $longitude, $category_search, $radius_meters = 15000){
        $googlePlaces = new PlacesApi(GOOGLE_KEY);
        $location = $latitude . "," . $longitude;
        $params   = array(
            "rankby" => "distance",
            "type"   => $category_search,
        );
        // on récupère les Lieux Google à proximité
        $response = $googlePlaces->nearbySearch($location, $radius_meters, $params);
        foreach ($response->get('results') as $key => $value) {
            // on récupère plus de détails sur chaque lieu
            $lieu_json       = $googlePlaces->placeDetails($value['place_id']);
            $place = new GooglePlaces();
            $lieu            = $lieu_json['result'];
            $place->setJson($lieu);
            $close_definitely = $lieu['permanently_closed'] ?? false;
            if (!$close_definitely) {
                $latitude        = $lieu['geometry']['location']['lat'];
                $place->setLatitude($latitude);
                $longitude       = $lieu['geometry']['location']['lng'];
                $place->setLongitude($longitude);
                $name            = $lieu['name'];
                $place->setName($name);
                $id              = $lieu['place_id'];
                $place->setGoogleId($id);
                $review          = $lieu['rating'] ?? 0;
                $place->setReview($review);
                $opening_hours   = (!empty($lieu['opening_hours']) && !empty($lieu['opening_hours']['periods'])) ? $lieu['opening_hours']['periods'] : null;
                $photo_reference = !empty($lieu['photos']) ? $lieu['photos'][0]['photo_reference'] : null;
                // on récupère la vrai adresse de la photo
                $picture_url     = $googlePlaces->photo($photo_reference, array("maxheight" => 720, "maxwidth" => 1080));
                if (preg_match('#maps.googleapis.com/maps/api/#', $picture_url)) {
                    $picture_url = null;
                }
                $place->setPictureUrl($picture_url);
                $categories = $lieu['types'];
                // TODO CONVERTIR CHAQUE CATEGORIE DE GOOGLE MAPS EN CATEGORIES DE ZONNY
                $place->setCategory($categories);
                if (!empty($opening_hours)) {
                    // on récupère le fuseau horaire des données horaires récupérées
                    $timezone_string             = 'FR';
                    $address_components = $lieu['address_components'];
                    foreach ($address_components as $key => $address_component) {
                        foreach ($address_component['types'] as $key_type => $type) {
                            if ($type == "country") {
                                // c'est cette itération qui contient le code du pays
                                $timezone_string = $address_component['short_name'];
                            }
                        }
                    }
                    $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $timezone_string);
                    foreach ($opening_hours as $key => $period) {
                        $current_day_time = strtotime(date('Y-m-d'));
                        // on vérifie s'il s'agit d'un système d'horaire inversé // qui se ferme avant minuit
                        if (!empty($period['close']['day']) && !empty($period['open']['day'])) {
                            if (($period['close']['day'] > $period['open']['day']) || ($period['close']['day'] == 0 && $period['open']['day'] == 6)) {
                                ${"day_" . $period['open']['day'] . "_invert"} = 1;
                            } else {
                                ${"day_" . $period['open']['day'] . "_invert"} = 0;
                            }
                        }
                        if (!empty($period['open']['day']) && !empty($period['open']['time'])) {
                            $start_date = substr(sprintf("%04d", $period['open']['time']), 0, 2) * 3600 + substr(sprintf("%04d", $period['open']['time']), 2, 4) * 60;
                            $start_date = $current_day_time + $start_date;
                            // on converti dans le bon fuseau horaire
                            $start_date_paris = new DateTime(date('Y-m-d H:i:s', $start_date), new DateTimeZone($timezone[0]));
                            $start_date_paris->setTimezone(new DateTimeZone('Europe/Paris'));
                            ${"day_" . $period['open']['day'] . "_open"} = $start_date_paris->format('Hi');
                        }
                        if (!empty($period['close']['day']) && !empty($period['close']['time'])) {
                            $duration_date = substr(sprintf("%04d", $period['close']['time']), 0, 2) * 3600 + substr(sprintf("%04d", $period['close']['time']), 2, 4) * 60;
                            // si la fermeture se fait le lendemain dans la nuit on doit alors ajouter une journée au compteur
                            if (!empty(${"day_" . $period['open']['day'] . "_invert"}) && ${"day_" . $period['open']['day'] . "_invert"} == 1) {
                                $duration_date += 86400;
                            }
                            $duration_date = $current_day_time + $duration_date;
                            // on converti dans le bon fuseau horaire
                            $duration_date_paris = new DateTime(date('Y-m-d H:i:s', $duration_date), new DateTimeZone($timezone[0]));
                            $duration_date_paris->setTimezone(new DateTimeZone('Europe/Paris'));
                            ${"day_" . $period['close']['day'] . "_close"} = $duration_date_paris->format('Hi');
                        }
                    }
                }
                $place->setDay0Open($day_0_open??null);
                $place->setDay0Close($day_0_close??null);
                $place->setDay0Invert($day_0_invert??null);
                $place->setDay1Open($day_1_open??null);
                $place->setDay1Close($day_1_close??null);
                $place->setDay1Invert($day_1_invert??null);
                $place->setDay2Open($day_2_open??null);
                $place->setDay2Close($day_2_close??null);
                $place->setDay2Invert($day_2_invert??null);
                $place->setDay3Open($day_3_open??null);
                $place->setDay3Close($day_3_close??null);
                $place->setDay3Invert($day_3_invert??null);
                $place->setDay4Open($day_4_open??null);
                $place->setDay4Close($day_4_close??null);
                $place->setDay4Invert($day_4_invert??null);
                $place->setDay5Open($day_5_open??null);
                $place->setDay5Close($day_5_close??null);
                $place->setDay5Invert($day_5_invert??null);
                $place->setDay6Open($day_6_open??null);
                $place->setDay6Close($day_6_close??null);
                $place->setDay6Invert($day_6_invert??null);
                // on vérifie si le lieu existe déjà dans la base de données ou non
                $check_place = clone $place;
                if($check_place->getFromDatabase()){
                    // l'objet existait déjà, on le met à jour
                    $place->updateToDataBase();
                }
                else {
                    // l'objet n'existait pas encore, on l'ajoute
                    $place->addToDataBase();
                }
            }
        }
    }

    /**
     * Calcule les horaires d'ouvertures du jour du lieu
     */
    public function get_current_opening_hours(){
        $day   = $this->get_day_number();
        $time  = date("Hi");
        // on calcule le level (temps restant en pourcentage de l'évenement)
        $actual_date      = time();
        $current_day_time = strtotime(date('Y-m-d'));
        $string_open_method = 'getDay' . $day . 'Open';
        $string_close_method = 'getDay' . $day . 'Close';
        $string_invert_method = 'getDay' . $day . 'Invert';
        $start_date       = substr(sprintf("%04d", $this->$string_open_method()), 0, 2) * 3600 + substr(sprintf("%04d", $this->$string_open_method()), 2, 4) * 60;
        $start_date       = $current_day_time + $start_date;
        $end_date    = substr(sprintf("%04d", $this->$string_close_method()), 0, 2) * 3600 + substr(sprintf("%04d", $this->$string_close_method()), 2, 4) * 60;
        // si la fermeture se fait le lendemain dans la nuit on doit alors ajouter une journée au compteur
        if ($this->$string_invert_method() == 1) {
            $end_date += 86400;
        }
        $end_date = $current_day_time + $end_date;
        if ($actual_date <= $start_date) {
            $pourcentage = 100;
        } elseif ($actual_date >= $end_date) {
            $pourcentage = 0;
        } else {
            $pourcentage = 100 - round(100 * ($actual_date - $start_date) / ($end_date - $start_date));
            $pourcentage = $pourcentage >= 0 && $pourcentage <= 100 ? $pourcentage : 0;
        }
        $this->start_time = date('Y-m-d H:i:s', $start_date);
        $this->end_time =  date('Y-m-d H:i:s', $end_date);
        $this->percentage_remaining = $pourcentage;
    }
    /**
     * retourne la liste des catégories intéressantes parmi les lieux de google
     * @return array tableaux contenant les noms des catégories
     */
    public static function get_research_categories()
    {
        return GOOGLE_PLACES_CATEGORIES_RESEARCH;
    }

    /**
     * retourne le numéro du jour dans la semaine selon la doc Google
     */
    public function get_day_number()
    {
        return idate('w', time());
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
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * @param mixed $google_id
     */
    public function setGoogleId($google_id): void
    {
        $this->google_id = $google_id;
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
    public function getPictureUrl()
    {
        return $this->picture_url;
    }

    /**
     * @param mixed $picture_url
     */
    public function setPictureUrl($picture_url): void
    {
        $this->picture_url = $picture_url;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getDay0Open()
    {
        return $this->day_0_open;
    }

    /**
     * @param mixed $day_0_open
     */
    public function setDay0Open($day_0_open): void
    {
        $this->day_0_open = $day_0_open;
    }

    /**
     * @return mixed
     */
    public function getDay1Open()
    {
        return $this->day_1_open;
    }

    /**
     * @param mixed $day_1_open
     */
    public function setDay1Open($day_1_open): void
    {
        $this->day_1_open = $day_1_open;
    }

    /**
     * @return mixed
     */
    public function getDay2Open()
    {
        return $this->day_2_open;
    }

    /**
     * @param mixed $day_2_open
     */
    public function setDay2Open($day_2_open): void
    {
        $this->day_2_open = $day_2_open;
    }

    /**
     * @return mixed
     */
    public function getDay3Open()
    {
        return $this->day_3_open;
    }

    /**
     * @param mixed $day_3_open
     */
    public function setDay3Open($day_3_open): void
    {
        $this->day_3_open = $day_3_open;
    }

    /**
     * @return mixed
     */
    public function getDay4Open()
    {
        return $this->day_4_open;
    }

    /**
     * @param mixed $day_4_open
     */
    public function setDay4Open($day_4_open): void
    {
        $this->day_4_open = $day_4_open;
    }

    /**
     * @return mixed
     */
    public function getDay5Open()
    {
        return $this->day_5_open;
    }

    /**
     * @param mixed $day_5_open
     */
    public function setDay5Open($day_5_open): void
    {
        $this->day_5_open = $day_5_open;
    }

    /**
     * @return mixed
     */
    public function getDay6Open()
    {
        return $this->day_6_open;
    }

    /**
     * @param mixed $day_6_open
     */
    public function setDay6Open($day_6_open): void
    {
        $this->day_6_open = $day_6_open;
    }

    /**
     * @return mixed
     */
    public function getDay0Close()
    {
        return $this->day_0_close;
    }

    /**
     * @param mixed $day_0_close
     */
    public function setDay0Close($day_0_close): void
    {
        $this->day_0_close = $day_0_close;
    }

    /**
     * @return mixed
     */
    public function getDay1Close()
    {
        return $this->day_1_close;
    }

    /**
     * @param mixed $day_1_close
     */
    public function setDay1Close($day_1_close): void
    {
        $this->day_1_close = $day_1_close;
    }

    /**
     * @return mixed
     */
    public function getDay2Close()
    {
        return $this->day_2_close;
    }

    /**
     * @param mixed $day_2_close
     */
    public function setDay2Close($day_2_close): void
    {
        $this->day_2_close = $day_2_close;
    }

    /**
     * @return mixed
     */
    public function getDay3Close()
    {
        return $this->day_3_close;
    }

    /**
     * @param mixed $day_3_close
     */
    public function setDay3Close($day_3_close): void
    {
        $this->day_3_close = $day_3_close;
    }

    /**
     * @return mixed
     */
    public function getDay4Close()
    {
        return $this->day_4_close;
    }

    /**
     * @param mixed $day_4_close
     */
    public function setDay4Close($day_4_close): void
    {
        $this->day_4_close = $day_4_close;
    }

    /**
     * @return mixed
     */
    public function getDay5Close()
    {
        return $this->day_5_close;
    }

    /**
     * @param mixed $day_5_close
     */
    public function setDay5Close($day_5_close): void
    {
        $this->day_5_close = $day_5_close;
    }

    /**
     * @return mixed
     */
    public function getDay6Close()
    {
        return $this->day_6_close;
    }

    /**
     * @param mixed $day_6_close
     */
    public function setDay6Close($day_6_close): void
    {
        $this->day_6_close = $day_6_close;
    }

    /**
     * @return mixed
     */
    public function getDay0Invert()
    {
        return $this->day_0_invert??0;
    }

    /**
     * @param mixed $day_0_invert
     */
    public function setDay0Invert($day_0_invert): void
    {
        $this->day_0_invert = $day_0_invert;
    }

    /**
     * @return mixed
     */
    public function getDay1Invert()
    {
        return $this->day_1_invert??0;
    }

    /**
     * @param mixed $day_1_invert
     */
    public function setDay1Invert($day_1_invert): void
    {
        $this->day_1_invert = $day_1_invert;
    }

    /**
     * @return mixed
     */
    public function getDay2Invert()
    {
        return $this->day_2_invert??0;
    }

    /**
     * @param mixed $day_2_invert
     */
    public function setDay2Invert($day_2_invert): void
    {
        $this->day_2_invert = $day_2_invert;
    }

    /**
     * @return mixed
     */
    public function getDay3Invert()
    {
        return $this->day_3_invert??0;
    }

    /**
     * @param mixed $day_3_invert
     */
    public function setDay3Invert($day_3_invert): void
    {
        $this->day_3_invert = $day_3_invert;
    }

    /**
     * @return mixed
     */
    public function getDay4Invert()
    {
        return $this->day_4_invert??0;
    }

    /**
     * @param mixed $day_4_invert
     */
    public function setDay4Invert($day_4_invert): void
    {
        $this->day_4_invert = $day_4_invert;
    }

    /**
     * @return mixed
     */
    public function getDay5Invert()
    {
        return $this->day_5_invert??0;
    }

    /**
     * @param mixed $day_5_invert
     */
    public function setDay5Invert($day_5_invert): void
    {
        $this->day_5_invert = $day_5_invert;
    }

    /**
     * @return mixed
     */
    public function getDay6Invert()
    {
        return $this->day_6_invert??0;
    }

    /**
     * @param mixed $day_6_invert
     */
    public function setDay6Invert($day_6_invert): void
    {
        $this->day_6_invert = $day_6_invert;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return json_encode($this->json);
    }

    /**
     * @param mixed $json
     */
    public function setJson($json): void
    {
        $this->json = $json;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review): void
    {
        $this->review = $review;
    }

    /**
     * @return float
     */
    public function getDistance(): ?float
    {
        return $this->distance;
    }

    /**
     * @param float|null $distance
     */
    public function setDistance(?float $distance): void
    {
        $this->distance = $distance;
    }




    
}
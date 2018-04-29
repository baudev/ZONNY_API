<?php
namespace ZONNY\Models\Accounts;

use ZONNY\Utils\Application;
use ZONNY\Utils\Database;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

/**
 * @SWG\Definition(
 *   definition="NormalFriend",
 *   type="object",
 *    required={"_id", "_name"}
 * )
 */
class Friend extends User implements \JsonSerializable
{
    /**
     * @var integer
     * @SWG\Property(
     *     description="The primary id",
     *     example=1652
     * )
     */
    private $_id;
    /**
     * @var string
     * @SWG\Property(
     *     description="Firstame",
     *     example="JOHN"
     * )
     */
    private $_first_name;
    /**
     * @var string
     * @SWG\Property(
     *     description="URL of the user's profile picture",
     *     example="https://dnntracker.atlassian.net/secure/useravatar?size=small&avatarId=17226"
     * )
     */
    private $_profile_picture_url;
    /**
     * @var float
     * @SWG\Property(
     *     description="Distance between friend and user",
     *     example=213.152
     * )
     */
    private $_distance;


    public function getFromDatabase(): bool
    {
        $is_allow_see_friend = parent::getFromDatabase();
        // la relation est bonne, on peut accéder aux données
        // on calcula la distance entre l'utilisateur et l'ami
        if($is_allow_see_friend && IS_POSTGRE_SQL){
            $req_distance = Database::getDb()->prepare("SELECT ST_Distance(geography(ST_Point(:user_longitude,:user_latitude)), geography(ST_Point(longitude,latitude)))/1000 as distance FROM members WHERE id=:id");
            $req_distance->execute(array(
               "user_latitude" => Application::getUser()->getLatitude(),
               "user_longitude" => Application::getUser()->getLongitude(),
               "id" => $this->getId()
            ));
            $this->setDistance($req_distance->fetch()['distance']);
        }
        return $is_allow_see_friend;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     * @throws PublicError
     */
    public function jsonSerialize(){
        // on regarde la relation entre l'utilisateur et l'ami (la décision de l'ami)
        $friend_link_by_friend = new FriendLink();
        // on défini l'identifiant de l'ami (car on regarde ici la décision de l'ami)
        $friend_link_by_friend->setUserId($this->getId());
        $friend_link_by_friend->setFriendId(Application::getUser()->getId());
        // on recupère les informations depuis la base de données
        if(!$friend_link_by_friend->getFromDatabase()){
            // on vérifie s'il ne s'agit pas de l'ami d'un ami. Dans ce cas, on affiche un mimnimum d'information
            // IMPORTANT dans le cas où un utilisateur invite des amis qui ne se connaissent pas
            if($this->getNumberCommunFriends()>0){
                // sera traité comme le dernier cas du script
            }
            else {
                throw new PublicError("The friend's id given doesn't seem to be your friend.", ErrorCode::NOT_A_FRIEND);
            }
        }
        // on regarde la relation entre l'utilisateur et l'ami (la décision de l'utilisateur)
        $friend_link_by_user = new FriendLink();
        // on défini l'identifiant de l'ami (car on regarde ici la décision de l'utilisateur)
        $friend_link_by_user->setUserId(Application::getUser()->getId());
        $friend_link_by_user->setFriendId($this->getId());
        // on recupère les informations depuis la base de données
        if(!$friend_link_by_user->getFromDatabase()){
            // on vérifie s'il ne s'agit pas de l'ami d'un ami. Dans ce cas, on affiche un mimnimum d'information
            // IMPORTANT dans le cas où un utilisateur invite des amis qui ne se connaissent pas
            if($this->getNumberCommunFriends()>0){
                // sera traité comme le dernier cas du script
            }
            else {
                throw new PublicError("The friend's id given doesn't seem to be your friend.", ErrorCode::NOT_A_FRIEND);
            }
        }

        // il s'agit d'un bon ami car les deux ont donné l'autorisation
        if($friend_link_by_friend->getRelation() && $friend_link_by_user->getRelation()){
            // on récupère les informations (on les détermine une par une pour éviter la diffusion d'une info supplémentaire lors de l'ajout d'une variable par exemple)
            $array = array(
                "id"                  => $this->getId(),
                "first_name"          => $this->getFirstName(),
                "last_name" => $this->getLastName(),
                "name" => $this->getName(),
                "profile_picture_url" => $this->getProfilePictureUrl(),
                "latitude" => $this->getLatitude(),
                "longitude" => $this->getLongitude(),
                "level"               => $this->getLevel(),
                "distance"  => $this->getDistance()
            );
        }
        else {
            // il s'agit d'un ami qui n'a pas l'autorisation de voir toutes les informations de l'utilisateur
            $array = array(
                "id"                  => $this->getId(),
                "first_name"          => $this->getFirstName() ?? $this->getName(),
                "profile_picture_url" => $this->getProfilePictureUrl()
            );
        }
        return $array;
    }

    /**
     * @return bool
     */
    public function is_location_valid():bool {
        // vérifie que la localisation de l'utilisateur est récente
        $current = new \DateTime();
        $last_location_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->getLocationLastCheckUp());
        $diff = $current->getTimestamp() - $last_location_datetime->getTimestamp();
        if($diff>NUMBER_SECONDS_LOCATION_IS_NO_MORE_VALID){
            return false;
        }
        else {
            return true;
        }
    }


    /**
     * @return float
     */
    public function getDistance(): ?float
    {
        if($this->getLatitude()==null || $this->getLongitude()==null){
            return null;
        }
        return $this->_distance;
    }

    /**
     * @param float $distance
     */
    public function setDistance(?float $distance): void
    {
        $this->_distance = $distance;
    }

}

// Ajoute la définition d'un bon ami
/**
 *  @SWG\Definition(
 *   definition="GoodFriend",
 *   type="object",
 *   allOf={
 *       @SWG\Schema(ref="#/definitions/NormalFriend"),
 *       @SWG\Schema(
 *           required={"_name"},
 *           @SWG\Property(property="_last_name", type="string", example="SMITH"),
 *           @SWG\Property(property="_name", type="string", description="Name including firstname and lastname", example="JOHN SMITH"),
 *           @SWG\Property(property="_latitude", type="number", format="float", example=48.569542),
 *           @SWG\Property(property="_longitude", type="number", format="float", example=2.48978),
 *           @SWG\Property(property="_level", type="integer", example=12),
 *       )
 *   }
 * )
 */
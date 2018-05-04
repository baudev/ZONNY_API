<?php
namespace ZONNY\Models\Accounts;

use ZONNY\Utils\Database;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;
/**
 * @SWG\Definition(
 *   definition="FriendLink",
 *   type="object",
 *    required={"id", "user_id", "friend_id", "authorization"}
 * )
 */
class FriendLink implements \JsonSerializable
{

    public CONST GOOD_FRIEND = 1;
    public CONST NOT_GOOD_FRIEND = 0;

    /**
     * @var integer
     * @SWG\Property(
     *     description="The primary id",
     *     example=153
     * )
     */
    private $id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The user_id of the relation's owner",
     *     example=862
     * )
     */
    private $user_id;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The friend_id of whose the relation is concerning",
     *     example=1652
     * )
     */
    private $friend_id;
    private $mutual_friends;
    private $mutual_likes;
    /**
     * @var integer
     * @SWG\Property(
     *     description="The relation value. 1 if the user has considered the friend has a good one. Otherwise, 0",
     *     enum={0,1}
     * )
     */
    private $relation;


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
     * @return bool
     */
    public function getFromDatabase():bool
    {
        $req = Database::getDb()->prepare('SELECT * from friends_links WHERE (user_id=:user_id AND friend_id=:friend_id) OR id=:id');
        $req->execute(array(
            "user_id" => $this->getUserId(),
            "friend_id" => $this->getFriendId(),
            "id" => $this->getId()
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

    public function addToDataBase()
    {
        $req = Database::getDb()->prepare("INSERT INTO friends_links (user_id, friend_id, mutual_friends, mutual_likes, relation) VALUES (:user_id, :friend_id, :mutual_friends, :mutual_likes, :relation)");
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
                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }

        $req->execute($array);
        // on insère l'id de la relation
        $this->setId(Database::getDb()->lastInsertId());
    }

    public function deleteFromDataBase()
    {
        $req = Database::getDb()->prepare('DELETE from friends_links WHERE id=?');
        $req->execute(array($this->getId()));
    }

    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE friends_links SET user_id = COALESCE(:user_id, user_id), friend_id = COALESCE(:friend_id, friend_id), mutual_friends = COALESCE(:mutual_friends, mutual_friends), mutual_likes = COALESCE(:mutual_likes, mutual_likes), relation = COALESCE(:relation, relation) WHERE id = :id');

        $array = array();
        // on défini le tableau contenant l'ensemble des variables
        foreach ($this as $key => $value) {
            // on récupère le nom du getter associé à la variable
            $key_upper = ucwords($key, "_");
            $key_upper = preg_replace("#_#", "", $key_upper);
            $method = 'get' . $key_upper;
            if (method_exists($this, $method)) {
                switch ($key) {
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
            if (method_exists($this, $method)) {
                switch ($key){
                    case 'mutual_friends':
                    case 'mutual_likes':
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
        return $this->id;
    }

    /**
     * @param mixed $id
     * @throws PublicError
     */
    public function setId($id): void
    {
        if (!empty($id) && !preg_match('#^\d+$#i', $id)) {
        throw new PublicError("Invalid number format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @throws PublicError
     */
    public function setUserId($user_id): void
    {
        if (!empty($user_id) && !preg_match('#^\d+$#i', $user_id)) {
            throw new PublicError("Invalid number format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->user_id = $user_id;
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
        if (!empty($friend_id) && !preg_match('#^\d+$#i', $friend_id)) {
            throw new PublicError("Invalid number format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->friend_id = $friend_id;
    }

    /**
     * @return mixed
     */
    public function getMutualFriends()
    {
        return $this->mutual_friends;
    }

    /**
     * @param mixed $mutual_friends
     * @throws PublicError
     */
    public function setMutualFriends($mutual_friends): void
    {
        if (!empty($mutual_friends) && !preg_match('#^\d+$#i', $mutual_friends)) {
            throw new PublicError("Invalid number format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->mutual_friends = $mutual_friends;
    }

    /**
     * @return mixed
     */
    public function getMutualLikes()
    {
        return $this->mutual_likes;
    }

    /**
     * @param mixed $mutual_likes
     * @throws PublicError
     */
    public function setMutualLikes($mutual_likes): void
    {
        if (!empty($mutual_likes) && !preg_match('#^\d+$#i', $mutual_likes)) {
            throw new PublicError("Invalid number format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        }
        $this->mutual_likes = $mutual_likes;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param mixed $authorization
     * @throws PublicError
     */
    public function setRelation($authorization): void
    {
        if (!empty($authorization) && !preg_match('#^[0-1]$#i', $authorization)) {
            throw new PublicError("Invalid authorization value format (must be 1 or 0).", ErrorCode::INVALID_AUTH_VARIABLE);
        }
        $this->relation = $authorization;
    }

}
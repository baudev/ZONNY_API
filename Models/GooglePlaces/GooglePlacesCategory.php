<?php

namespace ZONNY\Models\GooglePlaces;


use ZONNY\Utils\Database;

class GooglePlacesCategory implements \JsonSerializable
{

    private $_id;
    private $_name;
    private $_cat_id;


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
        $req = Database::getDb()->prepare('SELECT * from google_places_cat WHERE id=:id OR name=:name');
        $req->execute(array(
            "id" => $this->getId(),
            "name" => $this->getName(),
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
        $req = Database::getDb()->prepare("INSERT INTO google_places_cat (name, cat_id) VALUES (:name, :cat_id)");

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
                        break;

                    default:
                        $array[$key] = $this->$method();
                        break;
                }
            }
        }
        $req->execute($array);
        // on insère l'id de la catégorie
        $this->setId(Database::getDb()->lastInsertId());
    }

    public function deleteFromDataBase()
    {
        $req = Database::getDb()->prepare('DELETE from google_places_cat WHERE id=?');
        $req->execute(array($this->getId()));
    }

    /**
     * ATTENTION IL S'AGIT D'UNE MODIFICATION BRUTALE SANS COALESCE
     */
    public function updateToDataBase()
    {
        $req = Database::getDb()->prepare('UPDATE google_places_cat SET name=:name, cat_id=:cat_id  WHERE id=:id');

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
            // on enlève le premier underscore de la variable
            $key = substr_replace($key, "", 0, 1);
            if (method_exists($this, $method)) {
                switch ($key){
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
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
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
    public function getCatId()
    {
        return $this->_cat_id;
    }

    /**
     * @param mixed $cat_id
     */
    public function setCatId($cat_id): void
    {
        $this->_cat_id = $cat_id;
    }




}
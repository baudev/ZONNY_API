<?php

namespace ZONNY\Utils;

use Exception;
use PDO;

/**
 * Gère la connexion à la base
 *
 */
class Database
{

    private static $db;

    public static function connectPostgreSQL(){
        // Connexion à la base de données avec PDO
        self::setDb(new PDO('pgsql:host=' . DB_HOST_POSTGRE . ';dbname=' . DB_NAME_POSTGRE, DB_USERNAME_POSTGRE, DB_PASSWORD_POSTGRE, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )));
        // on vérifie que la table members existe. Si ce n'est pas le cas, on crée la structure de la base de données
        if(!self::isTableExists("members")){
            // on crée la base de données
            $sql_create = file_get_contents(dirname(__FILE__) . '/../database.sql');
            if(!empty($sql_create)) {
                self::getDb()->exec($sql_create);
            }
        }
    }

    /**
     * @param $table
     * @return bool
     */
    private static function isTableExists($table):bool {
        try {
            self::getDb()->query("SELECT 1 FROM $table LIMIT 1");
            return true;
        }catch (Exception $e){
            return false;
        }
    }

    /**
     * @return mixed
     */
    public static function getDb():?PDO
    {
        return self::$db;
    }

    /**
     * @param mixed $db
     */
    public static function setDb($db): void
    {
        self::$db = $db;
    }

}

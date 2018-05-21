<?php
namespace ZONNY\Utils;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


/**
 * Gère la connexion à la base
 *
 */
class Database
{

    private static $entity_manager;

    /**
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public static function getEntityManager()
    {
        if(empty(self::$entity_manager)){
            self::setEntityManager();
        }
        return self::$entity_manager;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public static function setEntityManager(): void
    {
        $isDevMode = DEBUG;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;

        $entitiesPath = [
            join(DIRECTORY_SEPARATOR, [__DIR__, "..", "Models"])
        ];

        $config = Setup::createAnnotationMetadataConfiguration(
            $entitiesPath,
            $isDevMode,
            $proxyDir,
            $cache,
            $useSimpleAnnotationReader
        );

        // database configuration parameters
        $conn = array(
            'driver'   => 'pdo_pgsql',
            'host'     => DB_HOST_POSTGRE,
            'charset'  => 'utf8',
            'user'     => DB_USERNAME_POSTGRE,
            'password' => DB_PASSWORD_POSTGRE,
            'dbname'   => DB_NAME_POSTGRE,
        );

        // obtaining the entity manager
        $entityManager = EntityManager::create($conn, $config);
        self::$entity_manager = $entityManager;
    }


}

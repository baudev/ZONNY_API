<?php
namespace ZONNY\Controllers\Accounts;


use Slim\Slim;
use ZONNY\Models\Helpers\Log;
use ZONNY\Repositories\Account\UserRepository;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class Authentificate
{

    /**
     * Authentifie l'utilisateur
     * @throws PublicError
     * @throws \Doctrine\ORM\ORMException
     */
    public function AuthUser(){
        $app = Slim::getInstance();
        // on recupère le header
        $headers = $app->request()->headers;
        // Vérification de l'en-tête d'autorisation
        if (isset($headers['Authorization']) && !empty($headers['Authorization'])) {
            // on cherche un utilisateur ayant cette clé
            $user = UserRepository::getRepository()->findOneBy(["keyApp" => $headers['authorization']]);
            // si la variable n'est pas nulle c'est qu'un utilisateur a été trouvé
            if($user != null){
                // l'utilisateur est authentifié
                Application::setUser($user);
                // on log l'utilisation de route
                $log = new Log();
                $log->setHackAttempt(false);
                $log->addToDatabase();
            }
            else {
                // l'utilisateur n'est pas authentifié
                // on ajoute la tentative d'accès au compte
                $log = new Log();
                $log->setHackAttempt(false);
                $log->addToDatabase();
                throw new PublicError("Invalid key_app. Unauthorized access.", ErrorCode::INVALID_KEY_APP);
            }

        } else {
            throw new PublicError("Key_app missing", ErrorCode::MISSING_KEY_APP);
        }

    }

}
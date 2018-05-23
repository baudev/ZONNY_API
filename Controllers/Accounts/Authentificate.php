<?php
namespace ZONNY\Controllers\Accounts;


use Slim\Route;
use Slim\Slim;
use ZONNY\Utils\Application;
use ZONNY\Models\Account\User;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\HackAttempts;
use ZONNY\Utils\Log;
use ZONNY\Utils\PublicError;

class Authentificate
{

    /**
     * @param null|Route $route
     * @throws PublicError
     */
    public function AuthUser(?Route $route=null){
        $app = Slim::getInstance();
        // on recupère le header
        $headers = $app->request()->headers;
        // Vérification de l'en-tête d'autorisation
        if (isset($headers['Authorization']) && !empty($headers['Authorization'])) {
            // on défini la clé entrée
            $user = new User();
            $user->setKeyApp($headers['authorization']);
            // on recupère l'utilisateur depuis la base de données
            // si true c'est qu'un utilisateur a été trouvé
            if($user->getFromDatabase()){
                // l'utilisateur est authentifié
                Application::setUser($user);
                // on log l'utilisation de route
                new Log($user, $app->request()->getResourceUri());
            }
            else {
                // l'utilisateur n'est pas authentifié
                // on ajoute la tentative d'accès au compte
                new HackAttempts($app->request()->getResourceUri(), $user);
                throw new PublicError("Invalid key_app. Unauthorized access.", ErrorCode::INVALID_KEY_APP);
            }

        } else {
            throw new PublicError("Key_app missing", ErrorCode::MISSING_KEY_APP);
        }

    }

}
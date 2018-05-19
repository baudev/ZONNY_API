<?php
namespace ZONNY\Utils;

use ZONNY\Models\Accounts\User;

class Application
{

    private static $user;
    private static $app;

    public static function init(&$app=null){
        // Pour les tests unitaires
        if($app!=null) {
            self::setApp($app);
            // on gère le comportement des erreurs
            self::handle_errors();
            // on active les Cors
            self::activateCors();
        }
        // On connecte l'application à la base de donnée
        Database::connectPostgreSQL();
        // on défini le fuseau horaire de Paris
        date_default_timezone_set('Europe/Paris');
    }

    /**
     * Vérifie différents élément sur une variable
     * @param $variable variable à vérifier
     * @param bool $required la variable est obligatoire ou non
     * @param bool $restrict_type si la variable doit répondre au critère d'une regex
     * @param string $regex regex correspondant à la variable
     * @param string $error_text texte si une erreur sur la regex est levée
     * @param int $error_code code de l'erreur si une erreur sur la regex est levée
     * @throws PublicError
     */
    public static function check_variables($variable, bool $required, bool $restrict_type, ?string $regex, ?string $error_text, ?int $error_code){
        // si la variable est requise alors on vérifie si elle n'est pas vide
        if($required){
            if(strlen(trim($variable))<=0){
                throw new PublicError("One or more variables are missing.", ErrorCode::MISSING_PARAMTERS);
            }
        }
        // si doit correspondrre au critère d'une regex
        if($restrict_type && strlen(trim($variable))>0){
            if(!preg_match('#'.$regex.'#', $variable)){
                // la variable ne correspond pas à ce qui est attendu
                throw new PublicError($error_text, $error_code);
            }
        }
    }

    /**
     * On active les CORS
     * Modifier CorsSlim::getParameters pour modifier les paramètres
     */
    private static function activateCors(){
        $cors = new CorsSlim(CorsSlim::getParameters());
        self::getApp()->add($cors);
    }

    /**
     * Gère l'affichage des erreurs
     */
    private static function handle_errors(){
        $app = self::getApp();
        // en fonction du mode debug ou non on affiche les erreurs
        if(!DEBUG) {
            $app->config('debug', false);
            $app->error(function (\ZONNY\Utils\PublicError $e) use ($app) {
                echo json_encode($e);
            });
            $app->error(function (\ZONNY\Utils\PrivateError $e) use ($app) {
                // on log l'erreur
                $e->log_error(Application::getUser());
                echo json_encode($e);
            });
            $app->error(function (\Exception $e) use ($app) {
                // on log l'erreur
                $private_error = new PrivateError($e->getMessage(), $e->getCode());
                $private_error->log_error(Application::getUser());
                echo json_encode(array("message" => "intern error", "code" => ErrorCode::UNKNOWN_TYPE));
            });
        }
    }

    /**
     * Retourne la réponse de requête
     * @param int $status_code
     * @param $response
     */
    public static function response(int $status_code, $response){
        ignore_user_abort(true);
        ob_start();
        header('Content-type: application/json; charset=utf-8');
        new HttpStatusCode($status_code);
        echo json_encode($response);
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();
        session_write_close();
    }

    /**
     * @return User
     */
    public static function getUser():?User
    {
        return self::$user;
    }


    /**
     * @param User $user
     */
    public static function setUser(User $user): void
    {
        self::$user = $user;
    }

    /**
     * @return mixed
     */
    public static function getApp()
    {
        return self::$app;
    }

    /**
     * @param mixed $app
     */
    public static function setApp($app): void
    {
        self::$app = $app;
    }

}
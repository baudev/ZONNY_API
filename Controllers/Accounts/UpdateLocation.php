<?php
namespace ZONNY\Controllers\Accounts;


use ZONNY\Models\Accounts\User;
use ZONNY\Models\GooglePlaces\GooglePlaces;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;

class UpdateLocation implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/account/location",
     *     summary="Update user account location",
     *     tags={"account"},
     *     description="Allow the user to update his current location.",
     *     operationId="updateAccountLocation",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="User's decimal latitude",
     *         in="formData",
     *         name="latitude",
     *         required=true,
     *         type="number",
     *         format="float"
     *     ),
     *     @SWG\Parameter(
     *         description="User's decimal longitude",
     *         in="formData",
     *         name="longitude",
     *         required=true,
     *         type="number",
     *         format="float"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="response", type="string", example="ok")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="error during request",
     *         @SWG\Items(ref="#/definitions/Error"),
     *     ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     */

    /**
     * PutLocalisation constructor.
     * @param $latitude
     * @param $longitude
     * @throws \ZONNY\Utils\PublicError
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    public function __construct($latitude, $longitude)
    {
        Application::check_variables($latitude, true, true, "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$", "Invalid latitude format", ErrorCode::INVALID_GPS_LOCATION);
        Application::check_variables($longitude, true, true, "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$", "Invalid longitude format", ErrorCode::INVALID_GPS_LOCATION);
        Application::getUser()->setLatitude($latitude);
        Application::getUser()->setLongitude($longitude);
        // on met à jour la localisation de l'utilisateur
        Application::getUser()->updateToDataBase();
        // on récupère les informations concernant l'utilisateur pour la recherche d'éléments à proximité
        // TODO EVENEMENTS FACEBOOK
        $this->search_google_places_near();
    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }


    /**
     * @throws \SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException
     */
    private function search_google_places_near(){
        if(Application::getUser()->has_Not_Created_Recents_Google_Places()){
            // l'utilisateur n'a pas récupéré de lieux Google lui même depuis longtemps
            // on récupère les lieux Google à proximité et on regarde leur date de création et
            $google_places = GooglePlaces::getNearPlaces(NUMBER_GOOGLE_PLACES_PROXIMITY);
            $count = 0;
            foreach ($google_places as $google_place){
                if($google_place->getDistance()<=MAX_DISTANCE_GOOGLE_PLACES_RESEARCH){
                    // le lieu est dans un périmètre intéressant
                    // TODO AJOUTER DATE DE CREATION AUX LIEUX GOOGLE MAPS ET VERIFIER SI LES LIEUX SONT ANCIENS
                    // on regarde si le lieu a été crée il y a longtemps
                    /*$current_date = new \DateTime();
                    $creation_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $google_place->get());
                    if($current_date->getTimestamp() - $creation_datetime->getTimestamp() > MAX_DURATION_BEFORE_REFRESH_GOOGLE_PLACE){

                    }*/
                    $count++;
                }
            }
            //
            if($count<NUMBER_UNTIL_GOOGLE_PLACES_RESEARCH_IS_NEEDED){
                // on lance la recherche d'évènement Google
                foreach (GooglePlaces::get_research_categories() as $category){
                    GooglePlaces::getPlacesFromGoogleDatabase(Application::getUser()->getLatitude(), Application::getUser()->getLongitude(), $category);
                }
            }
        }
    }

}
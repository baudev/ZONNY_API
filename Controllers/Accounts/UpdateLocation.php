<?php
namespace ZONNY\Controllers\Accounts;


use ZONNY\Models\Account\User;
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
     */
    public function __construct($latitude, $longitude)
    {
        Application::check_variables($latitude, true, true, "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$", "Invalid latitude format", ErrorCode::INVALID_GPS_LOCATION);
        Application::check_variables($longitude, true, true, "^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$", "Invalid longitude format", ErrorCode::INVALID_GPS_LOCATION);
        Application::getUser()->setLatitude($latitude);
        Application::getUser()->setLongitude($longitude);
        // on met à jour la localisation de l'utilisateur
        Application::getUser()->updateToDataBase();
    }

    public function jsonSerialize()
    {
        return array("response" => "ok");
    }


}
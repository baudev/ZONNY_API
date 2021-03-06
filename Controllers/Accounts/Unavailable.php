<?php
namespace ZONNY\Controllers\Accounts;

use ZONNY\Models\Account\User;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class Unavailable
{
    /**
     * @SWG\Put(
     *     path="/account/unavailable/start",
     *     summary="Make the user unavailable",
     *     tags={"account"},
     *     description="Allow the user to be unavailable until the specified datetime. Anybody will see the user on the map.",
     *     operationId="BecomeUnavailable",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Datetime until which the user will be unavailable. The datetime must be in the next 24h.",
     *         in="formData",
     *         name="datetime",
     *         required=true,
     *         type="string",
     *         format="datetime"
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
     * @param $datetime
     * @return array
     * @throws PublicError
     */
    public function setUnavailable($datetime)
    {
        Application::check_variables($datetime, true, true, "^^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$$", "Dtetime invalid format.", ErrorCode::INVALID_DATETIME);
        // on vérifie que la date est postérieure à la date actuelle
        $current_datetime = new \DateTime();
        $unavailable_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
        $diff = $unavailable_datetime->getTimestamp() - $current_datetime->getTimestamp();
        if($diff<0){
            throw new PublicError("Unavailable Datetime is already in the past.", ErrorCode::INVALID_DATETIME);
        }
        if($diff>MAX_DURATION_UNAVAILABLE){
            throw new PublicError("Unavailable Datetime can be posterior up to 24h the current datetime.", ErrorCode::UNAVAILABLE_MAX_24H);
        }
        Application::getUser()->setUnavailable($datetime);
        Application::getUser()->updateToDataBase();
        return array("response" => "ok");
    }


    /**
     * @SWG\Put(
     *     path="/account/unavailable/end",
     *     summary="Make the user available",
     *     tags={"account"},
     *     description="Allow the user to be available again.",
     *     operationId="BecomeAvailable",
     *     produces={"application/json"},
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
     * @return array
     * @throws PublicError
     */
    public function setFree(){
        $current_datetime = new \DateTime('1 minute ago');
        $current_datetime = $current_datetime->format('Y-m-d H:i:s');
        Application::getUser()->setUnavailable($current_datetime);
        Application::getUser()->updateToDataBase();
        return array("response" => "ok");
    }


}
<?php

namespace ZONNY\Controllers\GooglePlaces;


use ZONNY\Models\GooglePlaces\GooglePlaces;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;
use ZONNY\Utils\PublicError;

class GetGooglePlace implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/place_public/{id}",
     *     summary="Get all information concerning a public place",
     *     tags={"public place"},
     *     description="Return all information concerning a public place.",
     *     operationId="editEventRequest",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Id of the place.",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="object",
     *             ref="#/definitions/PublicPlace"
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

   private $_google_place;

    /**
     * GetGooglePlace constructor.
     * @param $_id
     * @throws PublicError
     */
    public function __construct($_id)
    {
        Application::check_variables($_id, true, true, "^[0-9]+$", "Invalid place_id.", ErrorCode::INVALID_EVENT_ID);
        $google_place = new GooglePlaces();
        $google_place->setId($_id);
        if($google_place->getFromDatabase()){
            $this->setGooglePlace($google_place);
        }
        else {
            throw new PublicError("The place doesn't seem to exist anymore.", ErrorCode::EVENT_REQUEST_NOT_FOUND);
        }
    }


    public function jsonSerialize()
    {
       return $this->getGooglePlace();
    }

    /**
     * @return mixed
     */
    public function getGooglePlace():GooglePlaces
    {
        return $this->_google_place;
    }

    /**
     * @param mixed $google_place
     */
    public function setGooglePlace($google_place): void
    {
        $this->_google_place = $google_place;
    }






}
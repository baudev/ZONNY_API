<?php

namespace ZONNY\Controllers\Accounts;

use ZONNY\Utils\Application;

/**
 * Permet de récupérer les informations concernant l'utilisateur
 * Class GetAccount
 * @package ZONNY\Controllers\Accounts
 */
class GetAccount implements \JsonSerializable
{

    /**
     * @SWG\Get(
     *     path="/account",
     *     summary="Get information about the current User",
     *     tags={"account"},
     *     description="All informations are not necessarily full.",
     *     operationId="getAccount",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Items(ref="#/definitions/User"),
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
    public function jsonSerialize()
    {
        return Application::getUser();
    }
}
<?php
namespace ZONNY\Controllers\Accounts;

use ZONNY\Utils\Application;

class DeleteAccount implements \JsonSerializable
{

    /**
     * @SWG\Delete(
     *     path="/account",
     *     summary="Delete user account",
     *     tags={"account"},
     *     description="Allow the user to delete all his information.",
     *     operationId="deleteAccount",
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

    public function jsonSerialize()
    {
        // TOOD
        // on supprime toutes les dépendances lidées à  l'utilisateur
        // on anonymise les données liées à l'utilisateur ne pouvant pas être supprimées
        // on supprime l'utilisateur
        Application::getUser()->deleteFromDataBase();
        return array("response" => "ok");
    }

}
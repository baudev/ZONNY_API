<?php
namespace ZONNY\Controllers\Events;

use ZONNY\Models\Events\Event;
use ZONNY\Models\Account\User;
use ZONNY\Utils\Application;
use ZONNY\Utils\ErrorCode;

class GetHistoric implements \JsonSerializable
{
    /**
     * @SWG\Get(
     *     path="/event/historic/{page}",
     *     summary="Get all events concerning the user",
     *     tags={"event"},
     *     description="Return all events concerning the user such the ones where he was a guests or the creator.",
     *     operationId="getHistoric",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         description="Number of desired page. Start to 1.",
     *         in="path",
     *         name="page",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="successful request",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="object", properties={
     *                   @SWG\Property(property="event", type="object", ref="#/definitions/Event"),
     *                   @SWG\Property(property="event_member_details", type="object", ref="#/definitions/EventMemberDetails")
     *             })
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

    private $_user;
    private $_page;


    /**
     * GetHistoric constructor.
     * @param User $user
     * @param $page
     * @throws \ZONNY\Utils\PublicError
     */
    public function __construct(User $user, $page)
    {
        Application::check_variables($page, true, true, "^[0-9]+$", "Invalid page parameter format.", ErrorCode::INVALID_VARIABLE_FORMAT);
        $this->setUser($user);
        $this->setPage($page);
    }

    public function jsonSerialize()
    {
        return Event::getHistoric($this->getUser(), $this->getPage());
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param mixed $user
     * @return GetHistoric
     */
    public function setUser($user)
    {
        $this->_user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * @param mixed $page
     * @return GetHistoric
     */
    public function setPage($page)
    {
        $this->_page = $page;
        return $this;
    }

}
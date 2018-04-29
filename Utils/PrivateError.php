<?php
namespace ZONNY\Utils;

use Exception;
use ZONNY\Models\Accounts\User;

/**
 * Erreurs destinées à l'administrateur
 * Class PrivateError
 * @package ZONNY\Utils
 */
class PrivateError extends Exception implements \JsonSerializable
{

    private $_code;
    private $_message;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setCode($code);
        $this->setMessage($message);
    }

    public function log_error(?User $user=null){
        $req = Database::getDb()->prepare("INSERT INTO errors (user_id, message, code, datetime) VALUES (:user_id, :message, :code, NOW())");

        $req->execute(array(
            'user_id'   => $user!=null?$user->getId():null,
            'message'     => $this->getMessage(),
            'code' => $this->getCode()
        ));
    }

    public function jsonSerialize()
    {
        return array("message" => "intern error", "code" => ErrorCode::UNKNOWN_TYPE);
    }

    /**
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->_code = $code;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->_message = $message;
    }

}

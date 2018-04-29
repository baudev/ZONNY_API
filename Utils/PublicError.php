<?php
namespace ZONNY\Utils;

use Exception;

/**
 * @SWG\Definition(
 *   definition="Error",
 *   type="object",
 *    required={"_code", "_message"}
 * )
 */
class PublicError extends Exception implements \JsonSerializable {

    /**
     * @var integer
     * @SWG\Property(
     *     description="The code of the corresponding error. Useful to translate the error in the application",
     *     example=16
     * )
     */
    private $_code;
    /**
     * @var string
     * @SWG\Property(
     *     example="Invalid key_app"
     * )
     */
    private $_message;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setCode($code);
        $this->setMessage($message);
    }

    public function jsonSerialize()
    {
        return array("message" => $this->getMessage(), "code" => $this->getCode());
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
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

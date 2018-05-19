<?php
namespace ZONNY\Utils;

use Exception;

/**
 * @SWG\Definition(
 *   definition="Error",
 *   type="object",
 *    required={"code", "message"}
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
    protected $code;
    /**
     * @var string
     * @SWG\Property(
     *     example="Invalid key_app"
     * )
     */
    protected $message;

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
        $this->code = $code;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

}

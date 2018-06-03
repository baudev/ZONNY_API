<?php
namespace ZONNY\Utils;

use DateTime;
use Exception;
use ZONNY\Models\Account\User;
use ZONNY\Models\Helpers\Error;

/**
 * Erreurs destinées à l'administrateur
 * Class PrivateError
 * @package ZONNY\Utils
 */
class PrivateError extends Exception implements \JsonSerializable
{

    protected $code;
    protected $message;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setCode($code);
        $this->setMessage($message);
    }

    /**
     * Ajoute l'erreur à la base de données
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addToDatabase(){
        $error = new Error();
        $error->setType("PrivateError");
        $error->setUrlRequest(Application::getApp()->request->getResourceUri());
        $error->setMessage($this->getMessage());
        $error->setCode($this->getCode());
        $error->setVariables(Application::getApp()->request->params());
        $error->setUser(Application::getUser());
        $error->setCreationDatetime(new DateTime());
        $entityManager = Database::getEntityManager();
        $entityManager->persist($error);
        $entityManager->flush();
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

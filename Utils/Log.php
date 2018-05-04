<?php
namespace ZONNY\Utils;

use ZONNY\Models\Accounts\User;

class Log
{
    private $operation;

    public function __construct(?User $user=null, $operation)
    {

        $this->setOperation($operation);

        $req = Database::getDb()->prepare('INSERT INTO logs (user_id, operation, ip, datetime) VALUES (:user_id, :operation, :ip, NOW())');
        $req->execute(array(
            "user_id" => $user!=null?$user->getId():null,
            "operation" => $this->getOperation(),
            "ip" => $_SERVER['REMOTE_ADDR']??null
        ));
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation??null;
    }

    /**
     * @param mixed $operation
     * @throws PrivateError
     */
    public function setOperation($operation): void
    {
        if($operation==null){
            throw new PrivateError("Log object error. Operation can't be null.", ErrorCode::DATABASE);
        }
        $this->operation = $operation;
    }

}

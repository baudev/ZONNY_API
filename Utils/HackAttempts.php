<?php
namespace ZONNY\Utils;


use ZONNY\Models\Account\User;

class HackAttempts
{

    private $key_app_intented;
    private $request;

    public function __construct(?string $request=null, ?User $user=null)
    {
        $this->setRequest($request);
        $this->setKeyAppIntented($user->getKeyApp());
        $this->addToDatabase();
    }

    private function addToDatabase(){
        $req = Database::getDb()->prepare("INSERT INTO hack_attempts (ip, request, key_app_intented, creation_datetime) VALUES (:ip, :request, :key_app_intented, :creation_datetime)");

        $req->execute(array(
            'ip'                => $_SERVER['REMOTE_ADDR'],
            'request'           => $this->getRequest(),
            'key_app_intented'  => $this->getKeyAppIntented(),
            'creation_datetime' => date('Y-m-d H:i:s'),
        ));
    }

    /**
     * @return mixed
     */
    public function getKeyAppIntented()
    {
        return $this->key_app_intented;
    }

    /**
     * @param mixed $key_app_intented
     */
    public function setKeyAppIntented($key_app_intented): void
    {
        $this->key_app_intented = $key_app_intented;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request): void
    {
        $this->request = $request;
    }


}
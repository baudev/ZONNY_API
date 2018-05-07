<?php
namespace ZONNY\Utils;


class DatetimeISO8601 implements \JsonSerializable
{

    private $datetime;

    /**
     * DatetimeISO8601 constructor.
     * @param $datetime
     */
    public function __construct($datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * @return mixed
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @param mixed $datetime
     */
    public function setDatetime($datetime): void
    {
        $this->datetime = $datetime;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return \DateTime::createFromFormat("Y-m-d H:i:se", $this->getDatetime())->format(\DateTime::ISO8601);
    }
}
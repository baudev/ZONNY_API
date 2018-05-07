<?php
namespace ZONNY\Utils;


use DateTime;
use DateTimeZone;

class DatetimeISO8601 extends DateTime implements \JsonSerializable
{

    private $datetime;

    public function __construct(string $time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->datetime = $time;
    }

    /**
     * @return mixed
     */
    public function getDatetime(): String
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

    public function __toString()
    {
        return $this->getDatetime();
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
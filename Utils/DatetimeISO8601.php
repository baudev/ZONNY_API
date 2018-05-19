<?php
namespace ZONNY\Utils;


use DateTime;
use DateTimeZone;

class DatetimeISO8601 extends DateTime implements \JsonSerializable
{

    public function __toString()
    {
        return $this->format("Y-m-d H:i:se");
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
        return $this->format("Y-m-d H:i:se");
    }
}
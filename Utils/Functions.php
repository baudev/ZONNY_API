<?php

namespace ZONNY\Utils;

class Functions{


    public static function randomFloat($min = -1, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public static function replace_key($arr, $oldkey, $newkey) {
        $arr[$newkey] = $arr[$oldkey];
        unset($arr[$oldkey]);
        return $arr;
    }

}

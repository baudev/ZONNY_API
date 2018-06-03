<?php

namespace ZONNY\Utils;

class Functions{


    /**
     * Génère un nombre aléatoire
     * @param int $min Borne inférieure
     * @param int $max Borne supérieure
     * @return float|int
     */
    public static function randomFloat($min = -1, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * Remplace dans un tableau donnée, la clé $oldkey par $newkey
     * @param $arr Tableau dans lequel la clé est remplacée
     * @param $oldkey Clé à remplacer
     * @param $newkey Clé par laquelle remplacer l'ancienne
     * @return array
     */
    public static function replace_key($arr, $oldkey, $newkey) : array {
        $arr[$newkey] = $arr[$oldkey];
        unset($arr[$oldkey]);
        return $arr;
    }

}

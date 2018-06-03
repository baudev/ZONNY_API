<?php

namespace ZONNY\Utils;
use ZONNY\Repositories\Account\UserRepository;

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization"
 * )
 */

class ApiKey {

    /**
     * Validation de la clé API de l'utilisateur
     * Si la clé API est là dans db, elle est une clé valide
     * @param String $api_key
     * @return boolean
     * @throws \Doctrine\ORM\ORMException
     */
    private static function isValidApiKey($api_key): bool
    {
        $results = UserRepository::getRepository()->findBy(["keyApp" => $api_key]);
        return $boolean = count($results) == 1 ? true : false;
    }

    /**
     * Génération aléatoire unique string pour utilisateur clé Api
     * @param $min
     * @param $max
     * @return int
     */
    private static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min;
        }
        // not so random...
        $log    = ceil(log($range, 2));
        $bytes  = (int) ($log / 8) + 1; // length in bytes
        $bits   = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    /**
     * Retourne une clé valide et non utilisée
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public static function generateApiKey(): string
    {
        do {
            $token        = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
            $codeAlphabet .= "0123456789";
            $max = strlen($codeAlphabet); // edited

            for ($i = 0; $i < API_KEY_CHARAC_NUMBER; $i++) {
                $token .= $codeAlphabet[self::crypto_rand_secure(0, $max - 1)];
            }
        } while (self::isValidApiKey($token));
        return $token;
    }

}


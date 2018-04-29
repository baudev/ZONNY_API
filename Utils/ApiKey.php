<?php

namespace ZONNY\Utils;

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
     */
    private static function isValidApiKey($api_key): bool
    {
        $stmt = Database::getDb()->prepare("SELECT id from members WHERE key_app = ?");
        $stmt->execute(array($api_key));
        return $boolean = $stmt->rowCount() == 1 ? true : false;
    }

    /**
     * Génération aléatoire unique string pour utilisateur clé Api
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

    public static function generateApiKey(): string
    {
        do {
            $token        = "";
            $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
            $codeAlphabet .= "0123456789";
            $max = strlen($codeAlphabet); // edited

            for ($i = 0; $i < 100; $i++) {
                $token .= $codeAlphabet[self::crypto_rand_secure(0, $max - 1)];
            }
        } while (self::isValidApiKey($token));
        return $token;
    }

}


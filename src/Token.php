<?php

namespace RandomToken;

class Token
{

    const CUSTOM    = 0;
    const NUMERIC   = 1;
    const LOWERCASE = 2;
    const UPPERCASE = 3;
    const NUMLOWER  = 4;
    const NUMUPPER  = 5;
    const ALL       = 6;


    private static $alphabet = [
        self::NUMERIC   => "0123456789",
        self::LOWERCASE => "abcdefghijklmnopqrstuvwxyz",
        self::UPPERCASE => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
        self::NUMLOWER  => "abcdefghijklmnopqrstuvwxyz0123456789",
        self::NUMUPPER  => "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        self::ALL       => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",

    ];


    private static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log    = ceil(log($range, 2));
        $bytes  = (int)($log / 8) + 1; // length in bytes
        $bits   = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }

    /**
     * @param $length
     * @param int $alphabet
     * @param string $customAlphabet
     * @return string
     */
    public static function generate($length, $alphabet = self::ALL, $customAlphabet = "")
    {

        if($alphabet == self::CUSTOM){
            $codeAlphabet = $customAlphabet;
        }
        else{
            $codeAlphabet = self::$alphabet[$alphabet];
        }


        $token = "";

        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure(0, $max)];
        }

        return $token;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 04/12/2019
 * Time: 20:19
 */

namespace App\Security;


class TokenGenerator
{
    private const ALPHABET = "ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    public function getRandomSecureToken(int $length= 30)
    {
        $token = '';
        $maxNumber = strlen(self::ALPHABET);
        for ($i = 0; $i< $length; $i++){
            $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
        }

        return $token;
    }
}
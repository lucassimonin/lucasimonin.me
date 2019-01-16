<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 2018-12-26
 * Time: 15:26
 */

namespace App\Utils;

class TokenGenerator implements TokenGeneratorInterface
{

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateToken(): string
    {
        return md5(uniqid());
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 26/12/2017
 * Time: 18:33
 */

namespace App\Services\Core;

/**
 * Class CoreService
 * @package App\Services\Core
 */
class CoreService
{
    /**
     * @return string
     * @throws \Exception
     */
    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}

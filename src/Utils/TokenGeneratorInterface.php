<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 2018-12-26
 * Time: 15:25
 */

namespace App\Utils;

/**
 * Interface SkuGeneratorInterface
 *
 * @package App\Utils
 */
interface TokenGeneratorInterface
{
    public static function generateToken(): string;
}

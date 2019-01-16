<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 2018-12-26
 * Time: 15:26
 */

namespace App\Tests\Utils;

use App\Utils\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function token_ok()
    {
        $this->assertEquals(32, strlen(TokenGenerator::generateToken()));
    }
}

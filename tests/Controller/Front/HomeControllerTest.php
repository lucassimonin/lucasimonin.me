<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 2018-12-27
 * Time: 19:58
 */

namespace App\Tests\Controller;


use App\Tests\Framework\WebTestCase;
/**
 * Class FrontControllerTest
 *
 * @package App\Tests\Controller
 * @group functional
 */
class HomeControllerTest extends WebTestCase
{
    public function provideUrls()
    {
        yield ['/'];
        yield ['/fr'];
        yield ['/en'];
    }

    /**
     * @test
     * @dataProvider provideUrls
     * @param $url
     */
    public function test_page_is_successful($url)
    {
        $this->logIn();
        $this->visit($url)
            ->responseOk();
    }

}
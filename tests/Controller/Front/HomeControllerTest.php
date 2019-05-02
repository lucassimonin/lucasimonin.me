<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 2018-12-27
 * Time: 19:58
 */

namespace App\Tests\Controller;


use App\Tests\Framework\PantherTestCase;

/**
 * Class FrontControllerTest
 *
 * @package App\Tests\Controller
 * @group functional
 */
class HomeControllerTest extends PantherTestCase
{
    public function provideUrls()
    {
        yield ['/', 'home_default'];
        yield ['/fr', 'home_fr'];
        yield ['/en', 'home_en'];
        yield ['/admin/users/list', 'user'];
    }

    /**
     * @test
     * @dataProvider provideUrls
     * @param $url
     * @param $name
     */
    public function test_page_is_successful($url, $name)
    {
        $this->visit($url)
            ->takeScreenShot($name);
        $this->assertEquals(1, $this->crawler->filter('h1')->count(), 'Page OK');
    }

}

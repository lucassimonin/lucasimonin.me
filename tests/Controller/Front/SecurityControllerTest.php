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
 * Class SecurityControllerTest
 *
 * @package App\Tests\Controller
 * @group functional
 */
class SecurityControllerTest extends PantherTestCase
{

    /**
     * @test
     */
    public function test_page_is_successful()
    {
        $this->visit('/login')
             ->takeScreenShot('login');

        $this->assertEquals(1, $this->crawler->filter('#username')->count(), 'Test element in Login page');
    }

    /**
     * @test
     */
    public function login_successful()
    {
        $this->visit('/login');
        $form = $this->crawler->filter('.form-signin')->form([
            '_username' => PantherTestCase::$defaultAdminMail,
            '_password' => 12345
        ]);

        $this->submitForm($form);
        $this->takeScreenShot('after_submit');
        $this->assertContains(trim($this->crawler->filter('.form-label-group')->filter('label')->text()), 'Google authenticator code', 'Login form');
    }

    /**
     * @test
     */
    public function login_with_bad_password()
    {
        $this->visit('/login');
        $form = $this->crawler->filter('.form-signin')->form([
            '_username' => PantherTestCase::$defaultUserMail,
            '_password' => 12345
        ]);

        $this->submitForm($form);
        $this->assertEquals(1, $this->crawler->filter('.alert-danger')->count(), 'Bad credentials.');
    }

}

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
 * Class SecurityControllerTest
 *
 * @package App\Tests\Controller
 * @group functional
 */
class SecurityControllerTest extends WebTestCase
{

    /**
     * @test
     */
    public function test_page_is_successful()
    {
        $this->visit('/login')
             ->responseOk();
    }

    /**
     * @test
     */
    public function login_successful()
    {
        $this->visit('/login')
             ->responseOk();

        $form = $this->crawler->filter('.form-signin')->form();
        $form['_username'] = WebTestCase::$defaultAdminMail;
        $form['_password'] = 12345;

        $this->submitForm($form);
        $this->assertContains(trim($this->crawler->filter('.form-label-group')->filter('label')->text()), 'Google authenticator code', 'Login OK');
    }

    /**
     * @test
     */
    public function login_with_bad_password()
    {
        $this->visit('/login')
             ->responseOk();

        $form = $this->crawler->filter('.form-signin')->form();
        $form['_username'] = WebTestCase::$defaultUserMail;
        $form['_password'] = 123;

        $this->submitForm($form);
        $this->assertEquals(1, $this->crawler->filter('.alert-danger')->count(), 'Bad credentials.');
    }

}
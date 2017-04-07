<?php

use PHPUnit\Framework\TestCase;

// These tests assume there is a user 'test@example.com' in the database and the
// pass is testPwor
class LoginTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLoginSuccessfulFunctionality()
    {
	unset($_SESSION);
        $email = 'test@example.com';
        $ctrl = new Controller();
        $ctrl->login($email,'testPwor');

        $this->assertEquals($_SESSION['email'], $email);

    }
    /**
     * @runInSeparateProcess
     */
    public function testLoginFailedFunctionality()
    {
        $this->expectOutputString("err_login_failed");
        $email = 'test@example.com';
        $ctrl = new Controller();
        // Give it the wrong password
        $ctrl->login($email,'wrong password');

    }
    /**
     * @runInSeparateProcess
     */
    public function testLoginPasswordCaseSensitivity()
    {

        $this->expectOutputString("err_login_failed");
        $email = 'test@example.com';
        $ctrl = new Controller();
        // Give it the wrong case password
        $ctrl->login($email,'testpwor');

    }

}

<?php

use PHPUnit\Framework\TestCase;

// These tests assume there is a user 'test@example.com' in the database and the
// pass is testPwor
class LoginTest extends TestCase
{

    // When customer logs in with valid details, customer is logged in & email is stored in session. 
    /**
     * @runInSeparateProcess
     */
    public function testLoginSuccessfulFunctionality()
    {
	unset($_SESSION);
        $email = 'd@d.d';
        $ctrl = new Controller();
        $ctrl->login($email,'ooo');

        $this->assertEquals($_SESSION['email'], $email);

    }

    // When customer logs in with incorrect password, login fails. 

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

    // When customer logs in with incorrect password due to incorrect letter case (ie lowercase rather than uppercase being used), login fails. 

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

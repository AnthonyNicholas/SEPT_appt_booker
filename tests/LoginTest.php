<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLoginSuccessfulFunctionality()
    {
        // This test assumes there is a user 'adam' in the database and the
        // pass is adam also
        $email = 'adam';
        $ctrl = new Controller();
        $ctrl->login($email,'adam');

        $this->assertEquals($_SESSION['email'], $email);

    }
    /**
     * @runInSeparateProcess
     */
    public function testLoginFailedFunctionality()
    {
        // This test assumes there is a user 'adam' in the database and the
        // pass is adam also
        $this->expectOutputString("err_login_failed");
        $email = 'adam';
        $ctrl = new Controller();
        // Give it the wrong password
        $ctrl->login($email,'wrong password');

    }

}

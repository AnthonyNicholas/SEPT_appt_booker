<?php

use PHPUnit\Framework\TestCase;

class RegisterCustTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testRegisterCustSuccessfulFunctionality()
    {
        // This test checks whether user 'test person' is added to the database
        
        $email = 'test@example.com';
        $fname = 'test';
        $lname =  'person';
        $address = 'test address';
        $phone = '12345678';
        $pword = 'testPword';
        $pword2 = 'testPword';

        $ctrl = new Controller();
        $ctrl->registerCust($email, $fname, $lname, $address, $phone, $pword, $pword2);

        // TODO - need to check of database to see if person has been added
        // $this->assertEquals( .......  );

    }
    /**
     * @runInSeparateProcess
     */
    public function testRegisterCustFailedFunctionality()
    {
        //TODO
    }

}

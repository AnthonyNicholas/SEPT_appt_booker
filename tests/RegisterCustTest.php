<?php

//  Tests of register customer functionality.  


use PHPUnit\Framework\TestCase;

class registerCustTest extends TestCase
{

    //  Test that when the customer registers with valid details, the customer is logged in. 
    /**
     * @runInSeparateProcess
     */
    public function testRegisterSuccessGoToLoginFunctionality()
    {
        
        $email = 'amy@aardvark.com';
        $fname = 'Amy';
        $lname = 'Aardvark';
        $address = '21 Aardvark Court, Melbourne, Vic 3000';
        $phone = '99999999';
        $pword = 'aardvark';
        $pword2 = 'aardvark';
        
        $ctrl = new Controller();
        $ctrl->registerCust($email, $fname, $lname, $address, $phone, $pword, $pword2);

        $this->assertEquals($_SESSION['email'], $email); // Is failing - don't know why.
    
    }
    
    //  Test that when the customer registers with valid details, details are correctly 
    // inserted into database. 
    
     /**
     * @runInSeparateProcess
     */
    public function testRegisterSuccessRecordedByDBFunctionality()
    {
        
        $email = 'amy@aardvark.com';
        $fname = 'Amy';
        $lname = 'Aardvark';
        $address = '21 Aardvark Court, Melbourne, Vic 3000';
        $phone = '99999999';
        $pword = 'aardvark';
        $pword2 = 'aardvark';
        
        $ctrl = new Controller();
        $ctrl->registerCust($email, $fname, $lname, $address, $phone, $pword, $pword2);

        $q = $ctrl->get_db()->prepare("SELECT * FROM Customers WHERE email = ?;");
        $q->bind_param('s', $email);
        $q->execute();
        $result = $q->get_result()->fetch_assoc();

        $this->assertEquals($result['email'], $email); 
        $this->assertEquals($result['fName'], $fname);
        $this->assertEquals($result['lName'], $lname);
        $this->assertEquals($result['address'], $address);
        $this->assertEquals($result['phoneNo'], $phone);
        $this->assertEquals($result['password'], $pword); 
        $this->assertEquals($result['password'], $pword2);

    }
    

}
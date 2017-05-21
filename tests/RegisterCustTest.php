<?php

//  Tests of register customer functionality.  


use PHPUnit\Framework\TestCase;

class registerCustTest extends TestCase
{

    protected $email;
    protected $fname;
    protected $lname;
    protected $address;
    protected $phone;
    protected $pword;
    protected $pword2;

    protected function setUp()
    {
        $this->email = 'amy@aardvark.com';
        $this->fname = 'Amy';
        $this->lname = 'Aardvark';
        $this->address = '21 Aardvark Court, Melbourne, Vic 3000';
        $this->phone = '99999999';
        $this->pword = 'aArdv4rk';
        $this->pword2 = 'aArdv4rk';
    }

    //  Test that when the customer registers with valid details, the customer is logged in. 
    /**
     * @runInSeparateProcess
     */
    public function testRegisterSuccessGoToLoginFunctionality()
    {
	$emtemp = $this->email."1";
        $ctrl = new Controller();
        $ctrl->registerCust($emtemp, $this->fname, $this->lname, $this->address, $this->phone, $this->pword, $this->pword2);

        // Delete test record from DB        
        $q = $ctrl->get_db()->prepare("DELETE FROM Customers WHERE email = ?;");
        $q->bind_param('s', $emtemp);
        $q->execute();

        $this->assertEquals($_SESSION['email'], $emtemp);
    
    }
    
    //  Test that when the customer registers with valid details, details are correctly 
    // inserted into database. 
    
     /**
     * @runInSeparateProcess
     */
    public function testRegisterSuccessRecordedByDBFunctionality()
    {
        $ctrl = new Controller();
        
        // Delete test record from DB        
        $q = $ctrl->get_db()->prepare("DELETE FROM Customers WHERE email = ?;");
        $q->bind_param('s', $this->email);
        $q->execute();
        
        $ctrl->registerCust($this->email, $this->fname, $this->lname, $this->address, $this->phone, $this->pword, $this->pword2);

        $q = $ctrl->get_db()->prepare("SELECT * FROM Customers WHERE email = ?;");
        $q->bind_param('s', $this->email);
        $q->execute();
        $result = $q->get_result()->fetch_assoc();

        $this->assertEquals($result['email'], $this->email); 
        $this->assertEquals($result['fName'], $this->fname);
        $this->assertEquals($result['lName'], $this->lname);
        $this->assertEquals($result['address'], $this->address);
        $this->assertEquals($result['phoneNo'], $this->phone);
        $this->assertEquals($result['password'], $this->pword); 
        $this->assertEquals($result['password'], $this->pword2);
        
        // Delete test record from DB        
        $q = $ctrl->get_db()->prepare("DELETE FROM Customers WHERE email = ?;");
        $q->bind_param('s', $this->email);
        $q->execute();


    }
    
    //  Tests that when the customer registers but email is already in DB, registration fails. 
    
     /**
     * @runInSeparateProcess
     */
    public function testRegisterFailureBecauseDuplicate()
    {
        $this->expectOutputString("duplicate");
        $ctrl = new Controller();
        $ctrl->registerCust($this->email, $this->fname, $this->lname, $this->address, $this->phone, $this->pword, $this->pword2);

        $ctrl->registerCust($this->email, $this->fname, $this->lname, $this->address, $this->phone, $this->pword, $this->pword2);

        // Delete test record from DB        
        $q = $ctrl->get_db()->prepare("DELETE FROM Customers WHERE email = ?;");
        $q->bind_param('s', $this->email);
        $q->execute();

    }
    
    //  Tests that when the customer registers but password2 doesn't match password, registration fails. 
    
     /**
     * @runInSeparateProcess
     */
    public function testRegisterFailurePasswordsDontMatch()
    {
        $this->expectOutputString("password");

        $this->pword2 = 'aaardvark';
        
        $ctrl = new Controller();
        $ctrl->registerCust($this->email, $this->fname, $this->lname, $this->address, $this->phone, $this->pword, $this->pword2);
    }


}

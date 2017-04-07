<?php

use PHPUnit\Framework\TestCase;

require_once('models/Customer.class.php');

class CustomerTest extends TestCase
{

    protected $email;
    protected $fname;
    protected $lname;
    protected $address;
    protected $phone;
    protected $pword;

    protected function setUp()
    {
        $this->email = 'amy@aardvark.com';
        $this->fname = 'Amy';
        $this->lname = 'Aardvark';
        $this->address = '21 Aardvark Court, Melbourne, Vic 3000';
        $this->phone = '99999999';
        $this->pword = 'aardvark';
    }

    /**
     * @runInSeparateProcess
     */

    public function testCreateCustomer()    {
        //$stub = $this->createMock(Customer::class);
        
        // Insert test customer into database (constructor for customer retreives customer from DB).
        
        $ctrl = new Controller();
        
        $q = $ctrl->get_db()->prepare("INSERT INTO Customers (email, fName, lName, address, phoneNo, password) VALUES  (?, ?, ?, ?, ?, ?);");
        $q->bind_param('ssssss', $this->email, $this->fname, $this->lname, $this->address, $this->phone, $this->pword);
        $q->execute();

        $testCustomer = new Customer($this->email,$ctrl->get_db());
        
        $this->assertEquals($testCustomer->data->fName, $this->fname);
        
        // Delete test record from DB        
        $q = $ctrl->get_db()->prepare("DELETE FROM Customers WHERE email = ?;");
        $q->bind_param('s', $this->get_email);
        $q->execute();
        
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testNotACustomer()    {
        $email = 'xxxzzz@xxxzzzyyyy.com';
        $ctrl = new Controller();
	try{
        	$testCustomer = new Customer($email,$ctrl->get_db());
	} catch( Exception $e)
	{
		$this->assertNotNull($e);
		//$this->assertNull($testCustomer);
	}
    }
}

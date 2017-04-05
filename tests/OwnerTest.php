<?php

use PHPUnit\Framework\TestCase;

require_once('models/BusinessOwner.class.php');

class OwnerTest extends TestCase
{

    protected $email;
    protected $fname;
    protected $lname;
    protected $pword;
    protected $address;
    protected $phone;
    protected $busName;
    

    protected function setUp()
    {
        $this->email = 'owner1@example.com';
        $this->fname = 'owner';
        $this->lname = 'juan';
        $this->pword = 'pw';
        $this->address = 'the moon';
        $this->phone = '99999999';
        $this->pword = 'password';
        $this->busName = 'moonbar';
    }

    /**
     * @runInSeparateProcess
     */

    public function testCreateOwner()    {
        
        $ctrl = new Controller();
        
        $q = $ctrl->get_db()->prepare("INSERT INTO BusinessOwner (fName, lName, email, password, address, phoneNo, busName) VALUES  (?, ?, ?, ?, ?, ?, ?);");
        $q->bind_param('ssssss', $this->fname, $this->lname, $this->email, $this->pword, $this->address, $this->phone, $this->busName);
        $q->execute();

        $testOwner = new BusinessOwner($this->email,$ctrl->get_db());
        
        $this->assertEquals($testOwner->data->fName, $this->fname);
        
        // Delete test record from DB        
        $q = $ctrl->get_db()->prepare("DELETE FROM BusinessOwner WHERE email = ?;");
        $q->bind_param('s', $this->email);
        $q->execute();
        
    }
    
}

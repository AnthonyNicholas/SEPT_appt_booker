<?php

// author: Jake Williams s3448342
// purpose: test functionality for adding an employee to the system

use PHPUnit\Framework\TestCase;

class addEmpTest extends TestCase
{
  
  // Test that add employee fails if last name is not numeric
  
    /**
     * @runInSeparateProcess
     */
    public function test_reject_lname()
    {
        $controller = new Controller();
         
        $result = $controller->addEmpOwner('abc', '0', null);
        
        $this->assertEquals($result, false);
    }
    
      /**
     * @runInSeparateProcess
     */
    public function test_reject_fname()
    {
        $controller = new Controller();
         
        $result = $controller->addEmpOwner('0', 'abc', null);
        
        $this->assertEquals($result, false);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_accept()
    {
         $controller = new Controller();
         
        $result = $controller->addEmpOwner('abc', 'def', array());
        
        $this->assertEquals($result, true);
    }
}
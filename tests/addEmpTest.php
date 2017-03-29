<?php

use PHPUnit\Framework\TestCase;

class addEmpTest extends TestCase
{
  
    /**
     * @runInSeparateProcess
     */
    public function test_reject_lname()
    {
        $controller = new Controller();
         
        $result = $controller->addEmpOwner('abc', '0');
        
        $this->assertEquals($result, false);
    }
    
      /**
     * @runInSeparateProcess
     */
    public function test_reject_fname()
    {
        $controller = new Controller();
         
        $result = $controller->addEmpOwner('0', 'abc');
        
        $this->assertEquals($result, false);


    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_accept()
    {
         $controller = new Controller();
         
        $result = $controller->addEmpOwner('abc', 'def');
        
        $this->assertEquals($result, true);
    }
}
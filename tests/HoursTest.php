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
    public function test_good_hours()
    {
        $controller = new Controller();
        
        $start = array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00");
        $end = array("23:00:00", "23:00:00", "23:00:00", "23:00:00", "23:00:00", "23:00:00", "23:00:00");

        $result = $controller->set_hours($start, $end);
        $this->assertEquals($result, true);
    }
    
    
    /**
     * @runInSeparateProcess
     */
    public function test_bad_hours()
    {
        $controller = new Controller();
        
        $start = array("23:30:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00");
        $end = array("23:00:00", "23:00:00", "23:00:00", "23:00:00", "23:00:00", "23:00:00", "23:00:00");
        $result = $controller->set_hours($start, $end);
        $this->assertEquals($result, false);
    }
    
    
}
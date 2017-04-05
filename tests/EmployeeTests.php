<?php

// author: Jake Williams s3448342
// purpose: test controller functionality relating to the addition/removal 
//          of employee shifts and working times from the database, and
//          retrieving such information for viewing by the users

use PHPUnit\Framework\TestCase;

class EmployeeTests extends TestCase
{
  
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_time_success() // can only run once, as duplicate times not accepted
    {
        $controller = new Controller();
        
        $result = $controller->add_working_time(8, new DateTime('2017-04-26 08:00:00'), new DateTime('2017-04-26 16:00:00'));
        
        $this->assertEquals($result, true);
        
    }
    
     /**
     * @runInSeparateProcess
     */
    public function test_add_working_time_duplicate() // can only run once, as duplicate times not accepted
    {
        $controller = new Controller();
        
        $result = $controller->add_working_time(8, new DateTime('2017-04-26 08:00:00'), new DateTime('2017-04-26 16:00:00'));
        
        $this->assertEquals($result, false);
        
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_time_bad_emp()
    {
        $controller = new Controller();
        
        $result = $controller->add_working_time(80, new DateTime('2017-04-01 08:00:00'), new DateTime('2017-04-01 16:00:00'));
        
        $this->assertEquals($result, false);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_time_bad_time()
    {
        $controller = new Controller();
        
        $result = $controller->add_working_time(8, new DateTime('2018-04-01 08:00:00'), new DateTime('2018-04-01 16:00:00'));
        
        $this->assertEquals($result, false);
    }
    
     /**
     * @runInSeparateProcess
     */
    public function test_get_working_hours_success() 
    {
        $controller = new Controller();
        
        $result = $controller->get_worker_hours(8);
        
        $this->assertNotNull($result);
        
    }
    
     /**
     * @runInSeparateProcess
     */
    public function test_workers_availability() 
    {
        $controller = new Controller();
        
        $result = $controller->workers_availability();
        
        $this->assertNotNull($result);
    }
    
     /**
     * @runInSeparateProcess
     */
    public function test_add_working_times_bad_time() 
    {
        $controller = new Controller();

        $time['date'][0] = "not a date";
        $time['start'][0] = "not a time";
        $time['end'][0] = "not a time";
        
        $result = $controller->add_working_times($time);
        
        $this->assertEquals($result, false);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_times_incomplete() 
    {
        $controller = new Controller();

        $time['end'][0] = "not a time";
        
        $result = $controller->add_working_times($time);
        
        $this->assertEquals($result, false);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_times_null() 
    {
        $controller = new Controller();

        $result = $controller->add_working_times(null);
        
        $this->assertEquals($result, false);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_times_success() 
    {
        $controller = new Controller();
        
        $time['date'][0] = "2017-04-19";
        $time['start'][0] = "08:00";
        $time['end'][0] = "16:00";
        $time['empID'] = 8;
        
        $result = $controller->add_working_times($time);
        
        $this->assertEquals($result, true);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_add_working_times_bad_emp() 
    {
        $controller = new Controller();
        
        $time['date'][0] = "2017-04-19";
        $time['start'][0] = "08:00";
        $time['end'][0] = "16:00";
        $time['empID'] = 80;
        
        $result = $controller->add_working_times($time);
        
        $this->assertEquals($result, false);
    }
    
  
}
<?php

// author: Jake Williams s3448342, Adam Young sxxxxxxx
// purpose: test various calendar functionality

use PHPUnit\Framework\TestCase;

require_once('models/Calendar.class.php');

class CalendarTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function test_success()
    {
        $controller = new Controller();
	// For the purpose of this test, we want to be a customer
	$_SESSION['type'] = 'customer';

	ob_start();
        $controller->getCustCal(2, 1);
        $page = ob_get_contents();
	ob_end_clean();
        $page = json_decode($page, true);
        $this->assertEquals($page['success'], true);
        $this->assertNotNull($page['content']);
    }
    
     /**
     * @runInSeparateProcess
     */
    public function test_fake_emp()
    {
        $controller = new Controller();
	ob_start();
        $controller->getCustCal(100, 1);
        $page = ob_get_contents();
	ob_end_clean();
        $page = json_decode($page, true);
        $this->assertEquals($page['success'], false);
    }
    
    /**
     * @runInSeparateProcess
     */
    public function test_bad_time()
    {
        $controller = new Controller();
	ob_start();
        $controller->getCustCal(5, -1);
        $page = ob_get_contents();
	ob_end_clean();
        $page = json_decode($page, true);
        $this->assertEquals($page['success'], false);
	
	unset($_SESSION);
    }
}
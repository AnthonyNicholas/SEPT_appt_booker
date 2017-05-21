<?php

use PHPUnit\Framework\TestCase;

/* Test whether the site correctly identifies if a user is logged in
 * and distinguishes owners and customers correctly 
 *   Authors: Adam, Jake
 */


class LoggedInTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testCustomerLoggedIn()
    {
        $ctrl = new Controller();
	    unset($_SESSION);
	    $_SESSION['email'] = "something";
	    $_SESSION['type'] = "customer";

        $result = $ctrl->custLoggedIn();

        $this->assertTrue($result);

    }
    
    
      /**
     * @runInSeparateProcess
     */
    public function testNoEmail()
    {
        $ctrl = new Controller();

	    unset($_SESSION);
	    $_SESSION['email'] = null;
	    $_SESSION['type'] = "customer";
	  
        $result = $ctrl->custLoggedIn();

        $this->assertFalse($result);

    }
    
          /**
     * @runInSeparateProcess
     */
    public function testBadType()
    {
         $ctrl = new Controller();
	    unset($_SESSION);
	    $_SESSION['email'] = "something";
	    $_SESSION['type'] = "customerNOT";

        $result = $ctrl->custLoggedIn();

        $this->assertFalse($result);

    }

    // When owner logs in with valid details, owner is logged in & email is stored in session. 
    /**
     * @runInSeparateProcess
     */
    public function testOwnerLoggedIn()
    {
	    
        $ctrl = new Controller();
	    unset($_SESSION);
	    $_SESSION['email'] = "something";
	    $_SESSION['type'] = "owner";

        $result = $ctrl->ownerLoggedIn();

        $this->assertTrue($result);

    }
        
    
    // When no owner is logged in, ie a customer is logged in, return false
    /**
     * @runInSeparateProcess
     */
    public function testOwnerNotLoggedIn()
    {
         $ctrl = new Controller();
	    unset($_SESSION);
	    $_SESSION['email'] = "something";
	    $_SESSION['type'] = "customer";
	    
       

        $result = $ctrl->ownerLoggedIn();

        $this->assertFalse($result);

    }
}
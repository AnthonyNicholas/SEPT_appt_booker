<?php
/**
 * Customer class
 *
 * Shell for the customer class. Should, given a customer number, pull the requested
 * data into class variables
 * 
 */
class Customer
{

    public var $custNo;
    public var $fName;
    public var $lName;
    public var $address;
    public var $phoneNo;
    public var $email;
    public var $password;

    public function __construct(  )
    {
        $this->id = $id;
    }

    public function readFromDb()
    {


    }

    public function printHtml()
    {

    }

}

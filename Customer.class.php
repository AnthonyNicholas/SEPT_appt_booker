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

    public var $id;

    public function __construct( $id )
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

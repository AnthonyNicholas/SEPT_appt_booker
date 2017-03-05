<?php
/**
 * Business Owner Class
 *
 * Shell for the Busiess Owner class. Should, given an id, pull the requested
 * data into class variables
 * 
 */
class BusinessOwner
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

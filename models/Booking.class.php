<?php
/**
 * Booking Class
 *
 * Shell for the booking class. Should, given a booking number, pull the requested
 * booking data into class variables
 * 
 */
class Booking
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

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

    public $empID;
    public $d;

    public function __construct( $empID, $timestamp, $db )
    {
        $this->empID = $empID;
        
        $this->d = $this->readFromDb($empID, $timestamp, $db);
    }

    public function readFromDb($empID, $timestamp, $db)
    {
        $sql = "SELECT CONCAT_WS(' ', e.fName, e.lName) as empName, dateTime as timestamp
                FROM CanWork w
                INNER JOIN Employees e ON w.empID = e.empID
                WHERE w.empID = ?
                AND w.dateTime = FROM_UNIXTIME(?+3600);
        "; // BAD PLEASE FIX TODO
        $stmt = $db->prepare($sql);
        // Insert our given username into the statement safely
        $stmt->bind_param('ss', $empID, $timestamp);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();

        return $res->fetch_object();

    }

    public function printHtml()
    {

    }

}

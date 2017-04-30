<?php
/**
 * CanWork Class
 * Authors: Jake Williams
 * Should, given an empId and timestamp, pull the requested CanWork from the database
 * booking data into class variables
 * 
 */

class CanWork
{
    private $db;
    
    public $data; // CanWork data object, values are exactly as they appear in database

    private $empId;
    private $dateTime;
    private $email;
    private $emp_fname;
    private $emp_lname;
    
    public function __construct( $empID, $dateTime, $db )
    {
        $this->db = $db;

        $this->load($empID, $dateTime, $db);
    }

    // load Booking table data into member variables, helper for the constructor
    public function load($empID, $dateTime, $db)
    {
        $dts = $dateTime->format('Y-m-d H:i:s');
        $q = $db->prepare("SELECT * FROM Bookings WHERE empID = ? AND dateTime = ?;");
        $q->bind_param('ss', $empID, $dts);
        $q->execute();
        
        $result = $q->get_result();
        $result = mysqli_fetch_array($result);
        
        $this->empId = $result['empID'];
        $this->email = $result['email'];
        $this->dateTime = $dateTime;
        
        $q = $db->prepare("SELECT fName, lName FROM Employees WHERE empID = ?;");
        $q->bind_param('s', $empID);
        $q->execute();
        
        $result = $q->get_result();
        $result = mysqli_fetch_array($result);
        
        $this->emp_fname = $result['fName'];
        $this->emp_lname = $result['lName'];
    }
    
    public function get_empId() { return $this->empId; }
    public function get_dateTime() { return $this->dateTime; }
    public function get_email() { return $this->email; }
    public function get_fname() { return $this->emp_fname; }
    public function get_lname() { return $this->emp_lname; }
}

<?php

 /**
  * Booking Class
  * Authors: Adam Young, Jake Williams
  * Purpose: A class to load and store booking data from 
  *         the database, based on the Booking table,
  *         and provide basic access to such data.
  */
  
class Booking
{
    public $d;
    
    private $empId;
    private $dateTime;
    private $email;
    private $emp_fname;
    private $emp_lname;
    
    private $type;
    
    public function __construct( $empID, $dateTime, $db )
    {
        $this->empID = $empID;

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
        
        if( ! ($result = mysqli_fetch_array($result)) )
            return; // No bookings here
        
        $this->empId = $result['empID'];
        $this->email = $result['email'];
        $this->dateTime = new DateTime($result['dateTime']);
        
        $type_key = $result['appType'];
        $q = $db->prepare("SELECT * FROM AppType WHERE id = ?;");
        $q->bind_param('s', $type_key);
        $q->execute();
        $result = $q->get_result();
        $result = mysqli_fetch_array($result);
        
        if ($result != null)
            $this->type = $result['appDesc'];
        else 
            $this->type = "Unspecified";
        
        
        
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
    public function get_type() { return $this->type; }
}

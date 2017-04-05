<?php
/**
 * CanWork Class
 *
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
    
    /**
     * The constructor for the Booking class
     * The toplevel variable determines whether we should fetch additional data related
     * to this booking
     */ 
    public function __construct( $empID, $dateTime, $db )
    {
        $this->db = $db;
       
        // If we cant find a CanWork, error
        //if ( ! ($this->data = $this->readFromDb($empID, $timestamp)) )
        //    throw new Exception("Unable to find Canwork at $timestamp with Employee: $empID");

       $this->load($empID, $dateTime, $db);
       
    }

    public function readFromDb($empID, $dt)
    {
        
        $sql = "SELECT *, dateTime as timestamp
                FROM CanWork
                WHERE empID = ?
                AND dateTime = ?;
        ";
        $stmt = $this->db->prepare($sql);
        // Insert our given username into the statement safely
        
        $dts = $dt->format('Y-m-d H:i:s');
        
        $stmt->bind_param('ss', $empID, $dts);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();

        return $res->fetch_object();

    }
    
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
        $this->dateTime = new DateTime($result['dateTime']);
        
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

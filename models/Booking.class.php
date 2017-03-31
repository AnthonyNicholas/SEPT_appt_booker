<?php
/**
 * Booking Class
 *
 * Shell for the booking class. Should, given a booking number, pull the requested
 * booking data into class variables
 * 
 * Currently readFromDb doesnt actually check the Booking table, as I have accidentally used this class for
 * fetching canWorks, I will shift this out - Adam
 * 
 */
require_once('Customer.class.php');

class Booking
{
    private $db;
    
    public $data; // Booking data object, values are exactly as they appear in database
    
    private $empId;
    private $dateTime;
    private $email;
    private $emp_fname;
    private $emp_lname;
    

    // I prepared another load function to store the data into separate variables, as above
    // I think it is more clear, personally

    // Fair enough, I did it this way to make it easier to print in views
    // What if I need customer fname and lname? - Adam

    /**
     * The constructor for the Booking class
     */ 
    public function __construct( $empID, $timestamp, $db)
    {
        $this->db = $db;
        $this->empID = $empID;
        
        // Load the actual booking
       $this->load($empID, $timestamp, $db);
       
        // If we cant find a booking, error
        if ( ! ($this->data = $this->readFromDb($empID, $timestamp)) )
            throw new Exception("Unable to find booking at $timestamp with Employee: $empID");
       
    }

    public function readFromDb($empID, $timestamp)
    {
        
        // for some reason, sometimes this function receives a timestamp
        // other time it receives a date time
        // this is a hack solution to make this work in both cases
        // should not be permanent fix
        try {
            $dt = new DateTime($timestamp);
        } catch (Exception $e) {
            $dt = new DateTime();
            $dt->setTimestamp($timestamp);
        }
        
        // I deleted that unix timestamp rubbish and created 
        // new datetimes before the queries to make this work
        // the correct times in our timezone are now being read/written to database
        // needs alot of testing
        $sql = "SELECT w.*, CONCAT_WS(' ', e.fName, e.lName) as empName, dateTime as timestamp
                FROM CanWork w
                INNER JOIN Employees e ON w.empID = e.empID
                WHERE w.empID = ?
                AND w.dateTime = ?;
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
    
    // alternative load function
    // Used to populate this objects attributes, rather than dumping it all in
    // a data object. Both methods have their merits
    public function load($empID, $dateTime, $db)
    {
        
        
        try {
            $dt = new DateTime($dateTime);
        } catch (Exception $e) {
            $dt = new DateTime();
            $dt->setTimestamp($dateTime);
        }
        $dts = $dt->format('Y-m-d H:i:s');
        
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
     
     // These functions serve to fetch the objects pulled from mySQL
     
     public function getThis() { return $this->data; } // This booking
    
    
}

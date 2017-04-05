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
    public $d;
    
    private $empId;
    private $dateTime;
    private $email;
    private $emp_fname;
    private $emp_lname;
    

    // I prepared another load function to store the data into separate variables, as above
    // I think it is more clear, personally
 
    public function __construct( $empID, $dateTime, $db )
    {
        $this->empID = $empID;
        
        $this->d = $this->readFromDb($empID, $dateTime, $db);
       
       $this->load($empID, $dateTime, $db);
    }

    public function readFromDb($empID, $dt, $db)
    {
        // store timestamp in this datetime to correct timezone
        
        $sql = "SELECT CONCAT_WS(' ', e.fName, e.lName) as empName, dateTime as timestamp
                FROM CanWork w
                INNER JOIN Employees e ON w.empID = e.empID
                WHERE w.empID = ?
                AND w.dateTime = ?;
        "; 
        $stmt = $db->prepare($sql);
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

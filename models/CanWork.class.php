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
    private $employee; // employee data object, values are exactly as they appear in database
    

    // I prepared another load function to store the data into separate variables, as above
    // I think it is more clear, personally

    // Fair enough, I did it this way to make it easier to print in views
    // What if I need customer fname and lname? - Adam

    /**
     * The constructor for the Booking class
     * The toplevel variable determines whether we should fetch additional data related
     * to this booking
     */ 
    public function __construct( $empID, $timestamp, $db, $toplevel = true )
    {
        $this->db = $db;
       
        // If we cant find a CanWork, error
        if ( ! ($this->data = $this->readFromDb($empID, $timestamp)) )
            throw new Exception("Unable to find Canwork at $timestamp with Employee: $empID");
        
        // Add employee object here
        if ( $toplevel )
        {
            if ( ! ($this->employee = $this->fetchEmployeeFromDb($empID)) )
                throw new Exception("Employee $empID could not be found for CanWork at time: ".$timestamp);
        }
       
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
    
    /**
     * Employee doesnt have an object, so fetching it here
     */
     private function fetchEmployeeFromDb($empId)
     {
         $sql = "SELECT *, CONCAT_WS(' ', fName, lName) as fullName FROM Employees WHERE empID = ?;";
        $stmt = $this->db->prepare($sql);
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $empId);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();

        return $res->fetch_object();
     }
     
     // These functions serve to fetch the objects pulled from mySQL
     
     public function getThis() { return $this->data; } // This CanWork
     public function getEmployee() { return $this->employee; }
    
    
}

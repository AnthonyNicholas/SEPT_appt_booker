<?php
/**
 * Customer class
 * Authors: Adam Young
 * Shell for the customer class. Should, given an email, then pull the requested
 * data into class variables
 * 
 */
require_once('Booking.class.php');

class Customer
{

    public $data; // userdata object, names are exactly as defined in SQL
    public $type = "customer";
    public $bookings;
    
    private $email;
    private $fullName;
    private $pw;

    public function __construct( $email, $db )
    {
        if ($this->data = $this->readFromDb($email, $db))
            return;
        else
            throw new Exception('Unable to find user, do they still exist?');
    }

    private function readFromDb($email, $db)
    {
        $stmt = $db->prepare("SELECT *, CONCAT_WS(' ', fName, lName) as fullName FROM Customers WHERE email = ?;");
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $email);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();
        
        if( $res->num_rows == 0 )
            return false;

        $res = $res->fetch_object();

        // Load the customers bookings
        $q = $db->prepare("SELECT * FROM Bookings WHERE email = ?;");
        $q->bind_param('s', $email);
        $q->execute();
        $result = $q->get_result();
        
        $this->bookings = array();
        
        while ($row = mysqli_fetch_array($result))
        {
            //////
            // DON'T STORE PAST BOOKINGS 
            /////
            $now = new DateTime();
            $dt = new DateTime($row['dateTime']);
            
            if ($now > $dt) // check timeslot isn't in the past
                continue;
            
            // store customers bookings
           $this->bookings[] = new Booking($row['empID'], $dt, $db);
        }

        $this->email = $res->email;
        $this->fullName = $res->fullName;
        
        return $res;
    }
 
    public function get_bookings() { return $this->bookings; }
    public function get_email() { return $this->email; }
    public function get_fullName() { return $this->fullName; }
}

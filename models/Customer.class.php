<?php
/**
 * Customer class
 *
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

    /**
     * The constructor for the Customer class
     * The getbookings variable determines whether we should fetch additional data related
     * to this customer
     */
    public function __construct( $email, $db, $getbookings = true )
    {
        if ( ! ($this->data = $this->readFromDb($email, $db, $getbookings)) )
            throw new Exception('Unable to find user, do they still exist?');
    }

    private function readFromDb($email, $db, $getbookings)
    {
        $stmt = $db->prepare("SELECT * FROM Customers WHERE email = ?;");
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $email);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();
        $res = $res->fetch_object();

        if ($getbookings)
        {
            $this->loadBookingsFromDb($email, $db);
        }

        return $res;
    }
    
    public function loadBookingsFromDb($email, $db)
    {
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
           $this->bookings[] = new Booking($row['empID'], $row['dateTime'], $db);
        }
    }
    
    public function getThis()
    {
        return $this->data;
    }
    
    public function get_bookings()
    {
       return $this->bookings;
    }

}

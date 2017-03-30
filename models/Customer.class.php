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
     * The toplevel variable determines whether we should fetch additional data related
     * to this booking
     */
    public function __construct( $email, $db, $toplevel = true )
    {
        if ( ! ($this->data = $this->readFromDb($email, $db, $toplevel)) )
            throw new Exception('Unable to find user, do they still exist?');
    }

    private function readFromDb($email, $db, $toplevel)
    {
        $stmt = $db->prepare("SELECT * FROM Customers WHERE email = ?;");
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $email);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();
        $res = $res->fetch_object();

        if ($toplevel) // We only want extras if this object is "top level"
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
               $this->bookings[] = new Booking($row['empID'], $row['dateTime'], $db, false); // Not top level
            }
        }

        return $res;
    }
    
    public function getCustomer()
    {
        return $this->data;
    }
    
    public function get_bookings()
    {
       return $this->bookings;
    }

}

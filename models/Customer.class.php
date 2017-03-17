<?php
/**
 * Customer class
 *
 * Shell for the customer class. Should, given an email, then pull the requested
 * data into class variables
 * 
 */
class Customer
{

    public $data; // userdata object, names are exactly as defined in SQL
    public $type = "customer";

    public function __construct( $email, $db )
    {
        if ($this->data = $this->readFromDb($email, $db))
            return;
        else
            throw new Exception('Unable to find user, do they still exist?');
    }

    private function readFromDb($email, $db)
    {
        $stmt = $db->prepare("SELECT * FROM Customers WHERE email = ?;");
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $email);
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

<?php
/**
 * Business Owner Class
 *
 * Shell for the Busiess Owner class. Should, given an email, then pull the requested
 * data into class variables
 * 
 */
class BusinessOwner
{

    public $data; // associative array of userdata
    public $type = "owner";

    public function __construct( $email, $db )
    {
        if ($this->data = $this->readFromDb($email, $db))
            return;
        else
            throw new Exception('Unable to find user, do they still exist?');
    }

    private function readFromDb($email, $db)
    {
        $stmt = $db->prepare("SELECT * FROM BusinessOwner WHERE email = ?;");
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $email);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();

        return $res->fetch_object();

    }



}

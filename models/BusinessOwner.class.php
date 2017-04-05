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

    public $fullName;
    public $email;
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
        $stmt = $db->prepare("SELECT *, CONCAT_WS(' ', fName, lName) as fullName FROM BusinessOwner WHERE email = ?;");
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $email);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();
        $obj = $res->fetch_object();
        
        $this->fullName = $obj->fullName;
        $this->email = $obj->email;

        return $obj;

    }

    public function get_email() { return $this->email; }
    public function get_fullName() { return $this->fullName; }

}

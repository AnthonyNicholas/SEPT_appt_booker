<?php

/**
  * AppType class
  * Authors: Anthony Nicholas
  * Purpose: A class to load and store appointment Type data from 
  *         the database, based on the appType table,
  *         and provide basic access to such data.
  */

class AppType
{
    private $id;
    private $appType;
    private $appDescription;
    private $appDuration;

        
    public function __construct($id, $db)
    {
        $this->id = $id;

        $this->load($id, $db);
    }
    
    // load appType table data into member variables, helper for the constructor
    public function load($id, $db)
    {
        
        $q = $db->prepare("SELECT * FROM AppType WHERE id= ?;");
        $q->bind_param('s', $id);
        $q->execute();
        
        $result = $q->get_result();
        $result = mysqli_fetch_array($result);
        
        $this->appType = $result['appType'];
        $this->appDesc = $result['appDesc'];
        $this->appDuration = $result['appDuration'];
    }
    
    public function get_id() { return $this->id; }
    public function get_appType() { return $this->appType; }
    public function get_appDesc() { return $this->appDesc; }
    public function get_appDuration() { return $this->appDuration; }
}

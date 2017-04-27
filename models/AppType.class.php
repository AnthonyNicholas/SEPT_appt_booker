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
    private $appType;
    private $appDescription;
    private $appDuration;

        
    public function __construct($appType, $db)
    {
        $this->appType = $appType;

        $this->load($appType, $db);
    }
    
    // load appType table data into member variables, helper for the constructor
    public function load($appType, $db)
    {
        
        $q = $db->prepare("SELECT * FROM AppType WHERE appType = ?;");
        $q->bind_param('s', $appType);
        $q->execute();
        
        $result = $q->get_result();
        $result = mysqli_fetch_array($result);
        
        $this->appDesc = $result['appDesc'];
        $this->appDuration = $result['appDuration'];
    }
    
    public function get_appType() { return $this->appType; }
    public function get_appDesc() { return $this->appDesc; }
    public function get_appDuration() { return $this->appDuration; }
}

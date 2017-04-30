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
    public $id;
    public $appType;
    public $appDescription;
    private $appDuration;

        
    public function __construct($id, $db, $data = array())
    {
        $this->id = $id;

        if ($db != null)
            $this->load($id, $db);
        else
        {
            $this->appType = $data['appType'];
            $this->appDescription = $data['appDesc'];
            $this->appDuration = $data['appDuration'];
        }   
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
    
    public static function get_all_types($db)
    {
        $q = $db->prepare("SELECT * FROM AppType");
        $q->execute();
        
        $result = $q->get_result();

        $types = array();
        
        $i = 0;
        
        while ($row = mysqli_fetch_assoc($result))
        {
            $types[$row['id']] = new AppType($row['id'], null, $row);  
        }

        return $types;  
    }
    
   /**
    * Fetches the available appointment types for a given employee and datetime
    * Authors: Adam Young
    */
    public static function get_types_for_employee($empNo, $db)
    {
        $q = $db->prepare("SELECT * FROM AppType WHERE id IN (
                                SELECT typeId FROM haveSkill WHERE empID = ?
                           );");
        $q->bind_param('s', $empNo);
        $q->execute();
        
        $result = $q->get_result();

        $types = array();
        
        $i = 0;
        
        while ($row = mysqli_fetch_assoc($result))
        {
            $types[$row['id']] = new AppType($row['id'], null, $row);  
        }

        return $types;  
    }
    
    public function get_id() { return $this->id; }
    public function get_appType() { return $this->appType; }
    public function get_appDesc() { return $this->appDescription; }
    public function get_appDuration() { return $this->appDuration; }
}

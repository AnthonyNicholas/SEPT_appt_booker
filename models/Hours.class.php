<?php

/** Hours class
 * Authors: Anthony
 * This class manage opening hours of business
*/

class Hours
{
    private $hoursArray = array('Sun' => array(), 'Mon' => array(), 'Tue' => array(), 'Wed' => array(), 'Thu' => array(), 'Fri' => array(), 'Sat' => array());

    public function __construct($db, $hoursArray = null)
    {
        if ($hoursArray != null) {
            $this->hoursArray = $hoursArray;
        }
        else{
            $this->load($db);
        }
    }
    
   public function load($db)
    {

        $q = $db->prepare("SELECT * FROM Hours;");
        $q->execute();
        $result = $q->get_result();
        
        // days are stored in db as ints sun = 0, mon = 1 etc.  Therefore to load into hoursArray need
        // to create array of keys which we can access by index.
        
        $keys = array_keys($this->hoursArray); 

        while ($row = mysqli_fetch_array($result))
        {
            $dayName = $keys[$row['day']];
            $this->hoursArray[$dayName]['open'] = $row['open'];        
            $this->hoursArray[$dayName]['close'] = $row['close'];        
            // $this->writeLog('Loading hours 4: this->hoursArray[row[day]][open] = '.$this->hoursArray[$dayName]['open'].", this->hoursArray[row[day]][close] = ".$this->hoursArray[$dayName]['close']);

        }    
    }
    
    public function checkWithinHours($timestamp)
    {
        $dts = $timestamp->format('Y-m-d H:i:s');
        $dw = $timestamp->format('D'); // day of week for value we are checking (numeric value Sun = 0)
        $time = $timestamp->format('H:i:s'); // time for value we are checking 

        // $dw = date("w", $timestamp->format('Y-m-d H:i:s')); // day of week for value we are checking - gives values 0 Sunday through to 6 Saturday
        // $time = date("H:i:s", $timestamp->format('Y-m-d H:i:s')); // time for value we are checking 

        $open = $this->hoursArray[$dw]['open'];
        $close = $this->hoursArray[$dw]['close'];
        
        // $this->writeLog("Checking hours: dts = ".$dts.", dw = ".$dw.", time = ".$time.", open = ".$open.", close = ".$close);
        
        
        if (($time < $open) || ($time > $close)){
            // $this->writeLog("Time was out of hours");
            return false;
        }

    // $this->writeLog("Time was inside hours");
        return true;
    }
    
    public function get_hoursArray() { return $this->hoursArray;}
    
    
    public function writeLog($msg)
    {
        $logline = PHP_EOL . $msg;
        file_put_contents("ant_debug.log", $logline, FILE_APPEND);
    }

    
}




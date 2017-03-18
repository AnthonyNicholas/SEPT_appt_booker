<?php

// This class can manage shifts, empty appointments, 
// and anything else defined by a start and end time

class Timeslot
{
    private $start;
    private $end;
    
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
    
    public function get_start() { return $this->start; }
    public function get_end() { return $this->end; }
    
/*    public function concatenate($timeslots) // takes a list of timeslots,
    {                                       // convert them into the minimum
                                            // number of timeslots which capture 
                                            // the same periods.    
                                            // aka appointments -> shift
        $dates = array();
        $dates[0] = new DateTime("2017-03-15 12:30:00");
        $dates[1] = new DateTime("2017-03-15 13:30:00");
        $dates[2] = new DateTime("2017-03-15 14:30:00");
        $dates[3] = new DateTime("2017-03-15 15:30:00");
        $dates[4] = new DateTime("2017-03-15 16:30:00");
        
        $ts = array();
        $ts[0] = new Timeslot($dates[0], $dates[1]);
        $ts[1] = new Timeslot($dates[1], $dates[2]);
        $ts[2] = new Timeslot($dates[3], $dates[4]);
        
        for ($i = 0; $i < count($timeslots) - 1; $i++)
        {
            if ($timeslots[$i]->get_end() <= $timeslots[$i+1]->get_start())
            {
                // merge i and i+1 
                
                echo $i;
                echo "<br>";
            }
        }
    }
    
    public function fragment($timeslot, $start, $length) // take a timeslot
    {                                                    // subtract from it a smaller timeslot
                                                         // return the original timeslot minus the fragment.    
                                                         // aka shift -> appointments 
   
   
    }                                           
  */  
    
}
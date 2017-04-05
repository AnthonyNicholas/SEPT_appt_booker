<?php

/** Timeslot class
 * This class can manage shifts, empty appointments, 
 * and anything else defined by a start and end time
*/

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
    
}
<?php

namespace Employee; // for clarity

class Shift
{
    private $start;
    private $end;
    
    public function __construct($start, $end)
    {
        $this->start = start;
        $this->end = end;
    }
    
    public function get_start() { return $this->start; }
    public function get_end() { return $this->end; }
}
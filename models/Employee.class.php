<?php

// Employee class
    // Dan, as you need to use this class as well, feel free to add to it or talk to me about it
    // Since your task is for adding new employees, you probably only need to call the constructor

class Employee
{
    private $id;
    private $fname;
    private $lname;
    private $shifts; // array containing all future shifts in the db
                     // converted from appointments
    
    public function __construct($id, $fname, $lname, $shifts)
    {
        $this->id = $id;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->shifts = $shifts; // could be empty if employee is being created for the first time
    }

    public function __destruct()
    {
        save();
    }
    
    private function save()
    {
        // deconstruct shifts back into appointments?
        // save to db
    }
    
    public function add_shifts($shifts)
    {
        // for owner add working hours
    }
    
    public function remove_shifts($shifts)
    {
        // ????
    }
    
    public function add_time($start, $end)
    {
        // append to shift/create new shift
    }
    
    public function get_fname() { return $this->fname; }
    public function get_lname() { return $this->lname; }
    public function get_shifts() { return $this->shifts; }
}
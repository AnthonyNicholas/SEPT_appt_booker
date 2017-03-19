<?php

class WorkerAvailability
{
    public function printHtml($employees) // $employees parameter is the return value 
    {                                     // of workers_availability() function
        for ($i = 0; $i < count($employees); $i++)
        {
            $fname = $employees[$i]['fName'];
            $lname = $employees[$i]['lName'];
            
            echo "<br>";
            echo "<div class=\"list-group\">";
            echo "<h4 class = \"list-group-item-heading\">".$fname." ".$lname."</h4>";
              
            if (count($employees[$i]['shifts']) == 0)
                echo "<p class = \"list-group-item-text\"> No working times recorded </p>";
              
            foreach($employees[$i]['shifts'] as $value)
            {
                $st = $value->get_start();
                $en = $value->get_end();
                $date_only = $st->format("d-m-Y");
                $start_time = $st->format("H:i:s");
                $end_time = $en->format("H:i:s");
                echo "<p class = \"list-group-item\">".$date_only."   ".$start_time." - ".$end_time."</p></a>";   
            }     
            
            echo "</div>";
            echo "<br>";
        }
    }
}
<?php

class WorkerAvailability
{
    public function printHtml($employees) // $employees parameter is the return value 
    {                                     // of workers_availability() function
    
     echo "<script src=\"js/main.js\"></script>"; 
    
    ?>
    <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <h3>Employee Times</h3><br>
            View available work times for each employee below.  Add additional availabilities for an employee via the add more times function.  
        </div>
    </div>
    
    <?php
        
     
        $employee_additions = array();
        $employee_additions = array_fill(0, count($employees), 0);
     
        for ($i = 0; $i < count($employees); $i++)
        {
            $fname = $employees[$i]['fName'];
            $lname = $employees[$i]['lName'];
            
            echo "<br>";
            echo "<form id=\"form\" method=\"POST\" action = \"../send_hrs.php\">";
            echo "<h4 class = \"list-group-item-heading\">".$fname." ".$lname."</h4>";
              
            foreach($employees[$i]['shifts'] as $value)
            {
                $st = $value->get_start();
                $en = $value->get_end();
                $date_only = $st->format("d-m-Y");
                $start_time = $st->format("H:i:s");
                $end_time = $en->format("H:i:s");
                echo "<div id = \"hrs_list\" class = \"list-group-item\">".$date_only."&nbsp;&nbsp;&nbsp;".$start_time." - ".$end_time.
                        
                    "</div>";   
                 
            }   
            
            echo "<div id = \"".$employees[$i]['empID']."\" class = \"list-group-item\"> Add more times...
                         <span class=\"pull-right\">
                            <button type=\"button\" class=\"btn btn-default btn-sm btn-circle\" onclick=\"add_hrs(".$employees[$i]['empID'].")\">
                                <i class=\"glyphicon glyphicon-plus\"></i>
                            </button>
                        </span>
                    </div>"; 
            
            
            /*
            "<span class=\"pull-right\">
                            <button type=\"button\" class=\"btn btn-default btn-xs btn-circle\">
                                <i class=\"glyphicon glyphicon-minus\"></i>
                            </button>
                        </span>
            */
            
 
            echo "</form>";
            echo "<br>";
        }
    }
}
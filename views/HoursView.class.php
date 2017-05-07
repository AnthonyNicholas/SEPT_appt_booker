<?php

class HoursView
{
    public function printHtml($result = '')
    {
        if ($result == "success")
            echo "<div class = \"text-success\"><h3> Times Successfully Updated </h3></br></div>"; 

        
        
        ?>





    <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <h3>Appointment Booking System</h3>
            Welcome to the appointment booking system.<br>
            Login to your existing account to continue to the booking page,
            or register a new account via the Register button in the top right
            corner of the screen.<br>  
        </div>
    
    
        <div class = "jumbotron jumbotron-fluid">
            
            
            
            
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            
                <div class="form-inline">
                    <label class="control-label col-sm-3">DAY</label>
                    <div class="col-sm-9">
                        <label class="form-control">START</label>
                        <label class="form-control">END</label>
                    </div>
                </div>
            
            <?php 
                
                $days = array("MON", "TUES", "WED", "THURS", "FRI", "SAT", "SUN");
                
                for ($i = 0; $i < 7; $i++)
                {
                    echo " <div class=\"form-inline\">
                    <label class=\"control-label col-sm-3\"> ".$days[$i].": </label>
                    <div class=\"col-sm-9\">
                        <input name=\"start[]\" type=\"time\" class=\"form-control\" id=\"start\" required>
                        <input name=\"end[]\" type=\"time\" class=\"form-control\" id=\"end\" required>
                    </div>
                </div>";
                    
                    
                }
            ?>
            
            
            
            
            
            
            
            
                
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
        
    <?php
        
        
        
    }
    
    
}
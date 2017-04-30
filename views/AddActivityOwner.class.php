<?php
 /**
  * AddActivityOwner View
  * Authors: Jake Williams
  * 
  */

class AddActivityOwner
{
    
    public function printHtml()
    {
        ?>
        
        <div class="container">
            <div class = "jumbotron jumbotron-fluid">
                <h3>Add a new Appointment Type</h3><br>
                Add a new Appointment Type into the system by entering its details below.    
            </div>
        </div>

        <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="appType">Name of new appointment type:*</label>
                    <div class="col-sm-9">
                        <input name="appType" type="text" class="form-control" placeholder="Enter name of new appointment type" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="appDuration">Duration:*</label>
                    <div class="col-sm-9">
                        <select name = "appDuration" class="form-control">
                            <option value = "1">30</option>
                            <option value = "2">60</option>
                            <option value = "3">90</option>
                            <option value = "4">120</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="appDesc">Description:*</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="5" name="appDesc"></textarea>
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Add New Appointment Type</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        
        <?php
    }
    
    public function printSuccessHtml()    {
        ?>
        <div>
            <p>Appointment Type added successfully.</p>
        </div>
        <?php
    }


    public function printCurrentAppTypes() 
    {                                     
        // $bookings = $customer->get_bookings();
        
        // echo "<h4 class = \"list-group-item-heading\"> My Bookings </h4>";
        
        // for ($i = 0; $i < count($bookings); $i++)
        // {
        //     $empId = $bookings[$i]->get_empId();
        //     $date_time = $bookings[$i]->get_dateTime();
        //     $fname = $bookings[$i]->get_fname();
        //     $lname = $bookings[$i]->get_lname();
        //     $type = $bookings[$i]->get_type();

        //     $date = $date_time->format("d-m-Y");
        //     $time = $date_time->format("H:i:s");
                
        //     echo "<div class = \"list-group-item\">".$date."&nbsp;&nbsp;&nbsp;".$time."&nbsp;&nbsp;&nbsp; with ".$fname." ".$lname."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$type."</div>";  
        // }
    }


}
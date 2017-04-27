<?php

class AddActivityOwner
{
    
    public function printHtml()
    {
        ?>
        
        <div class="container">
            <div class = "jumbotron jumbotron-fluid">
                <h3>Add a new Appointment Type</h3><br>
                Add an Appointment Type into the system by entering its details below.    
            </div>
        </div>

        <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="fname">Name of new appointment type:*</label>
                    <div class="col-sm-9">
                        <input name="fname" type="text" class="form-control" placeholder="Enter name of new appointment type" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="lname">Appointment Type Details:*</label>
                    <div class="col-sm-9">
                        <input name="lname" type="text" class="form-control" placeholder="Enter details about appointment type" required>
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Add Appointment Type</button>
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
            <p>Employee added successfully.</p>
        </div>
        <?php
    }

}
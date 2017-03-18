<?php

class AddEmpOwner
{
    
    public function printHtml()
    {
        ?>
        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="fname">First Name:</label>
                    <div class="col-sm-9">
                        <input name="fname" type="text" class="form-control" placeholder="Enter first name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="lname">Last Name:</label>
                    <div class="col-sm-9">
                        <input name="lname" type="text" class="form-control" placeholder="Enter last name">
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        
        <?php
    }

}
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
        
        <table class = "table-striped table-bordered">
            <tr><th>!! Links for Testing Site !!</th></tr>
            <tr><td><a href="register.php">Register</a></td></tr>
            <tr><td><a href="mainPageCust.php">Customer Mainpage</a></td></tr>
            <tr><td><a href="mainPageOwner.php">Owner Mainpage</a></td></tr>
            <tr><td><a href="logout.php">Logout</a></td></tr>
        </table>
        <?php
    }

}
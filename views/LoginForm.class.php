<?php
/**
 * The login form class will print out the html form specifically for the
 * type of user logging in. Maybe we could have a printHtml method for each
 * owners and customers in the same class?
 */
class LoginForm
{
    
    public function printHtml()
    {
    ?>

        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="email">Email address:</label>
                    <div class="col-sm-9">
                        <input name="email" type="email" class="form-control" id="email" placeholder="Enter email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="pwd">Password:</label>
                    <div class="col-sm-9">
                        <input name="pword" type="password" class="form-control" placeholder="Password">
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

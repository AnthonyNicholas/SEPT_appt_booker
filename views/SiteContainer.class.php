<?php
/**
 * This class includes headers, footers, navigation and any other
 * styling/backend content that is required
 */

class SiteContainer
{
    
    public $title = "Booking System";

    public function printHeader()
    {
    ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title><?php echo $this->title; ?></title>
                <meta content="initial-scale=1, width=device-width" name="viewport"/>
                <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet"/>
                <link href="bootstrap-3.3.7-dist/css/custom.css" rel="stylesheet"/>
            </head>
            
            <body>
    <?php
    }

    public function printFooter()
    {
    ?>

            <!--Close body divs-->
             </div>
             </div>

            <footer>
                    <p class="text-muted text-center">Contact information: <a href="mailto:someone@example.com"> someone@example.com</a>.</p>
            </footer>

            <!--Bootstrap javascript-->
            <script src="/jquery/jquery-3.1.1.js"></script>
            <script src="/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

        </body>

    </html>
    
    <?php
    }

    public function printNavCust()
    {
    ?>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="..."><span class="red">Appointment Booker</span></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li><a href="...">Book Appointment</a></li>
                        <li><a href="...">View Calender</a></li>
                        <li><a href="...">View History</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="login.php">Log Out</a></li>
                    </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid text-center">    
        <div class="col-sm-8 text-left"> 


    <?php
    }

    public function printNavOwner()
    {
    ?>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="mainpage.php"><span class="red">Appointment Booker</span></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li><a href="...">Book Appointment</a></li>
                    <li><a href="...">Add Employee</a></li>
                    <li><a href="...">Set Employee Times</a></li>
                    <li><a href="...">View Calander</a></li>

                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="login.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid text-center">    
        <div class="col-sm-8 text-left"> 

    <?php
    }

}

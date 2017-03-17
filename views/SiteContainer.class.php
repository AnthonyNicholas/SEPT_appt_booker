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
                <link href="/css/bootstrap.min.css" rel="stylesheet"/>
                <link href="/css/custom.css" rel="stylesheet"/>
                <link href="<?php echo CSS; ?>calendar.css" rel="stylesheet">
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
             </div>

            <footer>
                    <p class="text-muted text-center">Contact information: <a href="mailto:ourTeam@student.rmit.edu.au"> ourTeam@student.rmit.edu.au</a>.</p>
            </footer>

            <!-- Bootstrap & calendar core JavaScript
            ================================================== -->
            <!-- Placed at the end of the document so the pages load faster -->

            <!--Bootstrap javascript-->
            <script src="/jquery/jquery-3.1.1.js"></script>
            <script src="/js/bootstrap.min.js"></script>
            <script type="text/javascript" src='<?php echo JS; ?>cors.js'></script>
            <script type="text/javascript" src='<?php echo JS; ?>calendar.js'></script>
            <script type="text/javascript" src='<?php echo JS; ?>bootstrap-calendar.js'></script>


        </body>

        </html>
    
    <?php
    }

// Prints appropriate navigation bar.  Argument $type can be "owner", "customer" or for anything else 
// standard navigation bar is printed.

public function printNav($type = "none")
    {
    ?>
    <div class="container">
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
            
            <?php if ($type == "owner"){ ?>
                <ul class="nav navbar-nav">
                    <li><a href="...">Book Appointment</a></li>
                    <li><a href="...">Add Employee</a></li>
                    <li><a href="...">Set Employee Times</a></li>
                    <li><a href="...">View Calander</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            <?php }
            
            if ($type == "customer"){ ?>
                <ul class="nav navbar-nav">
                    <li><a href="...">Book Appointment</a></li>
                    <li><a href="...">View Calender</a></li>
                    <li><a href="...">View History</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
            <?php } ?>
            
            </div>
        </div>
    </nav>

    <div class="container-fluid text-center">    
        <div class="col-sm-2 text-left"></div> 
        <div class="col-sm-8 text-left"> 

    <?php
    }

}

<?php

class OwnerMainPageView    {
    
    public function printHtml($fName = "Sample", $lName = "Owner")    {
    ?>
    <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <?php echo "Welcome, ".$fName." ".$lName; ?>
        </div>
        <table>
            <tr><th>Item</th></tr>
            <tr><td><a href="owner-bookings.php">Edit Bookings</a></td></tr>
            <tr><td><a href="edit-details.php">Edit Your Details</a></td></tr>
            <tr><td><a href="logout.php">Logout</a></td></tr>
        </table>
    </div>

    <!--<div class="bookings"></div>-->
    
    <?php
        
    }

    public function printCalendar($empArray)
    {     
      foreach ($empArray as $e) {  
      ?>
        <div class="container">
          <div class="row marg-top "> 
            <!-- left column with employee details-->
            <div class="col-sm-4 hidden-xs">
              <div class="panel panel-default panel-address panel-calendar-height">
                <div class="company-tag label label-primary">Employee details</div>
                  <div class='panel-body'>
                    <h4> <?php echo $e['fName'].''.$e['lName']; ?> </h4>  
                  </div><!--end of panel-body-->        
                </div><!--end of panel-->    
              </div><!--end of col-sm-4-->
            
            <!-- right column with big calendar 1 -->
              <div class='col-sm-8 hidden-xs'> 
                <!--<div id="horizontal-calendar-big-wrapper-334455" data-calendar-id= < BROKEN FOR COMMENT ?php echo $e['empID']; ?> class="horizontal-calendar-big-wrapper carousel slide" data-wrap="false" data-ride="carousel" data-interval="false">-->
                <div id="horizontal-calendar-big-wrapper-<?php echo $e['empID']; ?>" data-calendar-id=<?php echo $e['empID']; ?> class="horizontal-calendar-big-wrapper carousel slide" data-wrap="false" data-ride="carousel" data-interval="false">
                  <div class='preloader'>
                  </div>
                </div><!--end of horizontal-calendar-big-->
              </div><!--end of col-sm-8-->
            </div><!--end of row-->
          </div>
        </div> 
    <?php
      }
    }
    
    
}
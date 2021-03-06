<?php
/**
 * OwnerMainPageView View
 * Holds all the HTML for the owner main page and calendars of booked appointments
 * Authors: Adam, Anthony
 */

class OwnerMainPageView    {
    
    public function printHtml($bo)
    {
    ?>
    <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <h3>Current Bookings</h3>
            <?php echo "Welcome to your business' appointment management application, ".$bo->get_fullName(); ?>.<br>
            Click on one of the navigation items above, or view a current booking by scrolling down to 
            relevant employee and selecting relevant time.  Full details of that booking will then be displayed.  
        </div>
    </div>
    
    <?php
        
    }

    public function printCalendar($empArray)
    {
    ?>
        <div class="container">
        <?php foreach ($empArray as $e) {  ?>
          <div style="display:inline" class="row marg-top " id="row-<?php echo $e['empID'];?>"> <!-- made hidden, added an id for choosing specific employee -->
            <!-- left column with employee details-->
            <div class="col-sm-4 hidden-xs">
              <div class="panel panel-default panel-address panel-calendar-height">
                <div class="company-tag label label-primary">Employee details</div>
                  <div class='panel-body'>
                    <h4> <?php echo $e['fName'].' '.$e['lName']; ?> </h4>  
                  </div><!--end of panel-body-->        
                </div><!--end of panel-->    
              </div><!--end of col-sm-4-->
            
            <!-- right column with big calendar 1 -->
            <div class='col-sm-8 hidden-xs'> 
              <div id="horizontal-calendar-big-wrapper-<?php echo $e['empID']; ?>" data-calendar-id=<?php echo $e['empID']; ?> class="horizontal-calendar-big-wrapper carousel slide" data-wrap="false" data-ride="carousel" data-interval="false">
                <div class='preloader'>
                </div>
              </div><!--end of horizontal-calendar-big-->
            </div><!--end of col-sm-8-->
          </div><!--end of row-->
        <?php } ?>
      </div><!--end of container-->
    <?php
    }
    
    
}
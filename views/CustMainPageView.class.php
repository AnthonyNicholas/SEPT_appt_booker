<?php

class CustMainPageView    {
    
    public function printHtml($u)    {
    ?>
    <div class="container">
      <div class = "jumbotron jumbotron-fluid">
          <h3>Book an Appointment</h3>
          <?php echo "Welcome to the appointment booking system, ".$u->get_fullName(); ?>.<br>
          Click on a desired appointment under a particular staff member to
          make a booking, or view current bookings by clicking the link in the
          navigation bar above.
      </div>
    </div>

    <?php
    }

    public function printCalendar($empArray)
    {     
    ?>
        <div class="container">
        <?php foreach ($empArray as $e) {  ?>
          <div class="row marg-top "> 
            <!-- left column with employee details-->
            <div class="col-sm-4 hidden-xs">
              <div class="panel panel-default panel-address panel-calendar-height">
                <div class="company-tag label label-primary">Employee details</div>
                  <div class='panel-body'>
                    <h4> <?php echo $e['fName'].' '.$e['lName']; ?> </h4> 
                    <img src="img/blank-profile.png" height="250" width="250">
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

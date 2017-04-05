<?php
class BookingView
{
    public function printConfirm($cw, $emp) // CanWork, Employee
    {
      $date = $cw->get_dateTime();
    ?>
        <div class="panel panel-default panel-address panel-appointment-height">
              <div class="company-tag label label-primary">Confirm appointment details</div>
            <div class='panel-body'>
              <h3>Appointment details</h3>
              <h4><?php echo Helper::sanitize($emp->fullName); ?></h4>
              <p>Date: <?php echo $date->format("d/m/Y"); ?> </p>
              <p>Time: <?php echo $date->format("H:i"); ?> </p>
              <form class='modal-form' id='modal_form_<?php echo $cw->timestamp ;?>' action="" method="post">
                <input type="hidden" name="a" value="create"/>
                <div><a class="btn btn-link" href="index.php">Return to calendar</a>
                <input type="submit" class="btn btn-primary" value="Confirm Booking"/></div>    
              </form>
            </div><!--end of panel-body-->        
          </div><!--end of panel-->
      <?php
    }
    
    public function printSuccess($cw, $emp) // CanWork, Employee
    {
      $date = $cw->get_dateTime();
    ?>
        <div class="panel panel-default panel-address panel-appointment-height">
              <div class="company-tag label label-primary">Confirm appointment details</div>
            <div class='panel-body'>
              <h3>Appointment Successfully Booked</h3>
              <h4><?php echo Helper::sanitize($emp->fullName); ?></h4>
              <p>Date: <?php echo $date->format("d/m/Y"); ?> </p>
              <p>Time: <?php echo $date->format("H:i"); ?> </p>
                <div><a class="btn btn-link" href="index.php">Return to main page</a></div>
            </div><!--end of panel-body-->        
          </div><!--end of panel-->
      <?php
      
    }
    
    public function printError()
    {
    ?>
        <div class="panel panel-default panel-address panel-appointment-height">
              <div class="company-tag label label-primary">Confirm appointment details</div>
            <div class='panel-body'>
              <h3>Appointment Booking Unsuccessful</h3>
                <div><a class="btn btn-link" href="index.php">Return to main page</a></div>
            </div><!--end of panel-body-->        
          </div><!--end of panel-->
      <?php
    }

    public function printOwnerBookingInfo($bk, $emp, $cust)
    {
      $date = $bk->get_datetime();
    ?>
        <div class="panel panel-default panel-address panel-appointment-height">
              <div class="company-tag label label-primary">Customer Appointment details</div>
            <div class='panel-body'>
              <h3>Appointment details</h3>
              <h4><?php echo Helper::sanitize($emp->fullName); ?></h4>
              <h4><?php echo "Customer Email: ".$cust->get_email(); ?></h4>
              <h4><?php echo "Customer Name: ".$cust->get_fullName(); ?></h4>
              <p>Date: <?php echo $date->format("d/m/Y"); ?> </p>
              <p>Time: <?php echo $date->format("H:i"); ?> </p>
            </div><!--end of panel-body-->        
          </div><!--end of panel-->
      <?php
    }

  
  
}

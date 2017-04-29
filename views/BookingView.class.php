<?php
class BookingView
{
    public function printConfirm($cw, $emp, $apptypes, $apptype) // CanWork, Employee
    {
      $date = $cw->get_dateTime();
    ?>
        <div class="panel panel-default panel-address panel-sept-appointment panel-appointment-height">
              <div class="company-tag label label-primary">Confirm appointment details</div>
            <div class="panel-heading"><h3>Confirm appointment details with <?php echo $emp->fullName;?></h3></div>
            <form class='modal-form' id='modal_form_<?php echo $cw->timestamp ;?>' action="" method="post">
              <table class="table" style="width: 400px">
                <tr>
                  <th>Date</th><td><?php echo $date->format("d/m/Y"); ?></td>
                  <th>Start</th><td><?php echo $date->format("H:i"); ?></td>
                  <th>Type</th>
                  <td colspan="3">
                    <select name="apptype">
                  <?php foreach($apptypes as $id => $type) {
                    $state = (!empty($apptype) && $apptype == $id) ? " selected":"";
                    echo "<option value=\"$id\"$state>$type</option>";
                  } ?>
                    </select>
                  </td>
                </tr>
              </table>
              <div class='panel-body'>
                <div class="sept-confirm">
                  <a class="btn btn-link" href="index.php">Return to calendar</a>
                  <input type="submit" class="btn btn-primary" value="Confirm Booking"/>
                </div>    
              </div><!--end of panel-body-->   
            </form>     
          </div><!--end of panel-->
      <?php
    }
    
    public function printConfirmOwner($cw, $emp, $apptypes, $apptype, $errorstr) // CanWork, Employee
    {
      $date = $cw->get_dateTime();
      // if a form has previously been submitted with a customer email, clean and fill in
      $cEmail = isset($_POST['custEmail']) ? strip_tags($_POST['custEmail']) : '';
    ?>
        <div class="panel panel-default panel-address panel-sept-appointment panel-appointment-height">
              <div class="company-tag label label-primary">Confirm appointment details</div>
          <div class="panel-heading"><h3>Confirm appointment details with <?php echo $emp->fullName;?></h3></div>
          <form class='modal-form' id='modal_form_<?php echo $cw->timestamp ;?>' action="" method="post">
              <table class="table" style="width: 400px">
                <tr>
                  <th>Date</th><td><?php echo $date->format("d/m/Y"); ?></td>
                  <th>Start</th><td><?php echo $date->format("H:i"); ?></td>
                  <th>Type</th>
                  <td colspan="3">
                    <select name="apptype">
                  <?php foreach($apptypes as $id => $type) {
                    $state = (!empty($apptype) && $apptype == $id) ? " selected":"";
                    echo "<option value=\"$id\"$state>$type</option>";
                  } ?>
                    </select>
                  </td>
                </tr>
                <tr><td colspan="6"><?php echo $errorstr;?></td></tr>
                <tr>
                  <th colspan="2">Customer Email</th>
                  <td colspan="4">
                    <input type="email" name="custEmail" value="<?php echo $cEmail;?>"/>
                  </td>
                </tr>
              </table> 
            <div class='panel-body'>
                <div class="sept-confirm">
                  <a class="btn btn-link" href="index.php">Return to calendar</a>
                  <input type="submit" class="btn btn-primary" value="Confirm Booking"/>
                </div>
            </div><!--end of panel-body-->
            </form>
          </div><!--end of panel-->
      <?php
    }
    
    public function printSuccess($bk, $emp, $cust = null) // CanWork, Employee
    {
      $date = $bk->get_dateTime();
      $enddate = $bk->get_endTime();
    ?>
        <div class="panel panel-default panel-address panel-sept-appointment panel-appointment-height">
              <div class="company-tag label label-primary">Appointment Details</div>
            <div class="panel-heading"><h3><?php echo $bk->get_type();?> appointment successfully booked with <?php echo $emp->fullName; ?></h3></div>
              <table class="table sept-borders">
                <tr>
                  <th>Date</th><td><?php echo $date->format("d/m/Y"); ?></td>
                  <th>Start</th><td><?php echo $date->format("H:i"); ?></td>
                  <th>End</th><td><?php echo $enddate->format("H:i"); ?></td>
                  <th>Duration</th><td><?php echo $bk->get_duration(); ?> minutes</td>
                </tr>
                <?php if (!empty($cust)):?>
                <tr>
                  <th>Customer Name</th><td><?php echo $cust->get_fullName(); ?></td>
                  <th>Customer Email</th><td><?php echo $cust->get_email(); ?></td>
                  
                </tr>
                <?php endif;?>
              </table>
            <div class='panel-body'>
                <div class="sept-confirm"><a class="btn btn-link" href="index.php">Return to main page</a></div>
            </div><!--end of panel-body-->        
          </div><!--end of panel-->
      <?php
      
    }
    
    public function printError($msg = "")
    {
    ?>
        <div class="panel panel-default panel-address panel-sept-appointment panel-appointment-height">
              <div class="company-tag label label-primary">Confirm appointment details</div>
            <div class='panel-body'>
              <h3>Appointment Booking Unsuccessful</h3>
              <p><?php echo $msg;?></p>
                <div class="sept-confirm"><a class="btn btn-link" href="index.php">Return to main page</a></div>
            </div><!--end of panel-body-->        
          </div><!--end of panel-->
      <?php
    }

    public function printOwnerBookingInfo($bk, $emp, $cust)
    {
      $date = $bk->get_datetime();
      $enddate = $bk->get_endtime();
    ?>
        <div class="panel panel-default panel-address panel-sept-appointment panel-appointment-height">
            <div class="company-tag label label-primary">Customer Appointment details</div>
            <div class="panel-heading"><h3><?php echo $bk->get_type();?> appointment with <?php echo $emp->fullName;?></h3></div>
              <table class="table sept-borders">
                <tr>
                  <th>Date</th><td><?php echo $date->format("d/m/Y"); ?></td>
                  <th>Start</th><td><?php echo $date->format("H:i"); ?></td>
                  <th>End</th><td><?php echo $enddate->format("H:i"); ?></td>
                  <th>Duration</th><td><?php echo $bk->get_duration(); ?> minutes</td>
                </tr>
                <tr>
                  <th>Customer Name</th><td><?php echo $cust->get_fullName(); ?></td>
                  <th>Customer Email</th><td><?php echo $cust->get_email(); ?></td>
                  
                </tr>
              </table>
              <div class="panel-body">
                <div class="sept-confirm"><a class="btn btn-link" href="index.php">Return to main page</a></div>
              </div><!--end of panel-body-->
          </div><!--end of panel-->
      <?php
    }

  
  
}

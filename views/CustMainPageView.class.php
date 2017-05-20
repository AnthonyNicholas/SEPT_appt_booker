<?php
/**
 * CustMainPageView View
 * Holds all the HTML for the customer main page and calendars
 * Authors: Adam, Anthony
 */
class CustMainPageView    {
    
    public function printHtml($u, $types='', $employees='')    {
      echo "<script src=\"js/main.js\"></script>";
      if (!empty($types))
      {
        echo "\n<script type=\"text/javascript\">";
        echo "types=".json_encode($types).";";
        echo "</script>\n";
      }
      if (!empty($employees))
      {
        echo "\n<script type=\"text/javascript\">";
        echo "employees=".json_encode($employees).";";
        echo "</script>\n";
      }
    ?>
    <div class="container">
      <div class = "jumbotron jumbotron-fluid">
        <h3>Book an Appointment</h3>
        <?php if (!empty($u)):?>
          <?php echo "Welcome to the appointment booking system, ".$u->get_fullName(); ?>.
        <?php endif;?>

        Click on a desired appointment under a particular staff member to
        make a booking, or view current bookings by clicking the link in the
        navigation bar above.
        <p></p>
       
        
         <div class="row">
                    <br>
                    </div>
                  



            <div class="row">
              <!--<div class="form-group">-->
                <label class="control-label col-sm-4" for="selectAppType">Select appointment type:</label>
                  <div class="col-sm-8">
                    <input id="selectAppType" type="text" list="types" class="form-control" oninput="set_type(types,employees)"/>
                      <datalist id="types">
                        
                        <?php //echo '<pre>'; print_r($types); echo '</pre>';
                          foreach ($types as $key => $value)
                            echo "<option id=\"".$value->get_id()."\">".$value->get_appType()."</option>";
                        ?>
                      </datalist>
        
                    </div>
                  </div> <!-- end row-->
                  
                  <div class="row">
                    <br>
                    </div>
             
                  
                  <div class="row">
              <!--<div class="form-group">-->
                <label class="control-label col-sm-4" for="selectAppType">Select employee:</label>
                  <div class="col-sm-8">
                    <input id="emp_search" type="text" list="emps" class="form-control" oninput="toggle_emp(employees)"/>
                      <datalist id="emps">
                        <?php
                        for ($i=0; $i<count($employees); $i++)
                            echo "<option id=\"opt_".$employees[$i]['empID']."\">".$employees[$i]['fName']." ".$employees[$i]['lName']."</option>";
                        ?>
                      </datalist>
        
                    </div>
                  </div> <!-- end row-->

        
      </div> <!-- end jumbotron-->
    </div> <!-- end container-->
    <?php
    }

    public function printCalendar($empArray, $types = array())
    {     
    ?>
        <div class="container">
        <?php foreach ($empArray as $e) {  ?>
          <div class="row marg-top type-<?php echo isset($types[$e['empID']]) && count($types[$e['empID']]) ? implode(' type-', array_keys($types[$e['empID']])):''; ?>" id="row_<?php echo $e['empID']?>"> 
            <!-- left column with employee details-->
            <div class="col-sm-4 hidden-xs">
              <div class="panel panel-default panel-address panel-calendar-height">
                <div class="company-tag label label-primary">Employee details</div>
                  <div class='panel-body'>
                    <h4> <?php echo $e['fName'].' '.$e['lName']; ?> </h4> 
                    <img src="img/blank-profile.png" height="250" width="250">
                    <?php if (isset($types[$e['empID']]) && count($types[$e['empID']]) != 0):?>
                      <p>Services: <?php echo implode(', ', $types[$e['empID']]);?> </p>
                    <?php endif; ?>
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

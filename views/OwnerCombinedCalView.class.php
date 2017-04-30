<?php
/**
 * OwnerCombinedCalView View
 * Holds all the HTML for the all cookings combined in one calendar view
 * Authors: Anthony
 */

class OwnerCombinedCalView    {
    
    public function printHtml($fName = "Sample", $lName = "Owner")    {
    ?>
    <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <h3>Combined view of all Bookings</h3>
            <br>
            Select and click on a current booking to view full details of that booking.  
        </div>
    </div>

            
        </div>
    </div>

    <!--<div class="bookings"></div>-->
    
    <?php
        
    }

    public function printCalendar()
    {     
      ?>
        <div class="container">
          <div class="row marg-top "> 
            <!-- left column with employee details-->
            <div class="col-sm-4 hidden-xs">
              <div class="panel panel-default panel-address panel-calendar-height">
                <div class="company-tag label label-primary">Employee details</div>
                  <div class='panel-body'>
                    <h4> Combined Employee View </h4>  
                  </div><!--end of panel-body-->        
                </div><!--end of panel-->    
              </div><!--end of col-sm-4-->
            
            <!-- right column with big calendar 1 -->
              <div class='col-sm-8 hidden-xs'> 
                <div id="horizontal-calendar-big-wrapper-99" data-calendar-id=99 class="horizontal-calendar-big-wrapper carousel slide" data-wrap="false" data-ride="carousel" data-interval="false">
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
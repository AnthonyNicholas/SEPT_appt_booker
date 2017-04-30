<?php
/**
 * CustHelpPage View
 * Holds all the HTML for the customer help page
 * Authors: Dan
 */
/*TODO
Make booking images
View created bookings*/

class CustHelpPage    {
    public function printHtml()    {
        ?>
        <div class = "jumbotron jumbotron-fluid">
        <div>
            <h2>How to make a booking</h2>
        </div>
        <div>
            <img src="help-imgs/how3makebook.PNG" style="width:900px;height:210px;">
            <p>Select an appointment type and the staff member you would like to see</p>
            <img src="help-imgs/how3makebook1.PNG" style="width:900px;height:300px;">
            <p>Select by clicking on the time, and confirm the booking. Taken
            bookings will appear greyed out and cannot be selected</p>
            <img src="help-imgs/how3makebook2.PNG" style="width:900px;height:200px;">
            <p>Confirm your appointment and type by clicking 'confirm'</p>
            <img src="help-imgs/how3makebook3.PNG" style="width:900px;height:200px;">
            <p>A confirmation message will appear</p>
        </div>
        <div>
            <h2>Viewing your current bookings</h2>
        </div>
        <div>
            <img src="help-imgs/mybookings.PNG" style="width:900px;height:150px;">
            <p>Scroll through your current bookings. Details will include
            the time of the appointment with your staff member and the appointment
            type.</p>
        </div>
        </div>
        <?php
    }
}
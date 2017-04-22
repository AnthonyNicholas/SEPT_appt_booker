<?php
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
            <p>Scroll to the staff member you would like to see</p>
            <img src="help-imgs/how2makebook.PNG" style="width:900px;height:305px;">
            <p>Find the day of the week and the time you would like to
            see a staff member</p>
            <img src="help-imgs/how2makebook1.PNG" style="width:900px;height:200px;">
            <p>Select by clicking on the time, and confirm the booking. Taken
            bookings will appear greyed out and cannot be selected</p>
            <img src="help-imgs/how2makebook2.PNG" style="width:900px;height:200px;">
            <p>A success message will appear with your booking details</p>
        </div>
        <div>
            <h2>Viewing your current bookings</h2>
        </div>
        <div>
            <img src="help-imgs/mybookings.PNG" style="width:900px;height:150px;">
            <p>Scroll through your current bookings. Details will include
            the time of the appointment with your staff member.</p>
        </div>
        </div>
        <?php
    }
}
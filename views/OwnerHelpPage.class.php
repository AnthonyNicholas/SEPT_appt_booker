<?php
/*TODO
View Bookings text + images
Adding employees images
Adjust worker times text + images
*/

class OwnerHelpPage    {
    public function printHtml()    {
        ?>
        <div class = "jumbotron jumbotron-fluid">
        <div>
            <h2>The Mainpage</h2>
        </div>
        <div>
            <p>The mainpage provides a complete overview of your employees.
            Each employee will have a designated spot that shows a 7 day 
            outlook on their current bookings.</p>
            <p>When a booking is taken the time a booking has been made is
            clickable and will take you to the details of booking, including
            the customer email and the customer name.</p>
            <img src="help-imgs/empinfo.PNG"  style="width:800px;height:200px;">
        </div>
        
        <div>
            <h2>Adding Employees</h2>
        </div>
        <div>
            <h3>You must add employees before you can set and view
            their times.</h3>
        </div>
        <div>
            <img src="help-imgs/add-emp.PNG" style="width:700px;height:90px;">
            <p>Click on the 'add employee' tab</p>
            <img src="help-imgs/add-emp 2.PNG" style="width:700px;height:150px;">
            <p>Enter the first and last name of the employee. Note that
            both fields are required</p>
            <img src="help-imgs/add-emp 3.PNG" style="width:700px;height:150px;">
            <p>Click 'Add Employee'</p>
            <img src="help-imgs/add-emp 4.PNG" style="width:700px;height:200px;">
            <p>When an employee is added, a confirmation message will
            appear.</p>
        </div>
        
        <div>
            <h2>Adjusting Worker Availability</h2>
        </div>
        <div>
            <h3>To view your employees' booking slots on the mainpage and the 
            combined employee view, times must be set for an employee's
            working hours.</h3>
        </div>
        <div>
            <img src="help-imgs/worker-available.PNG" style="width:700px;height:90px;">
            <p>Click on the 'Employee Times' tab.</p>
            <img src="help-imgs/worker-available1.PNG" style="width:900px;height:200px;">
            <p>Scroll to the staff member you would like to edit 
            working hours for.</p>
            <img src="help-imgs/worker-available2.PNG" style="width:900px;height:150px;">
            <p>At the bottom of an employee's name, the 'add more times' option
            should have an add or '+' button beside it.</p>
            <img src="help-imgs/worker-available3.PNG" style="width:900px;height:150px;">
            <p>Add the date and time the employee will be available and press
            submit.</p>
        </div>
        
        <div>
            <h2>Combined Worker View</h2>
        </div>
        <div>
            <p>Get a complete overview of all your employees' bookings for the 
            week. Click on a time to view detailed information about a booking.
            </p>
        </div>
        </div>
        <?php
    }
}
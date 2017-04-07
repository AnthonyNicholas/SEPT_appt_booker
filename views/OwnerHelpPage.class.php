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
            <h2>Adding Employees</h2>
        </div>
        <div>
            <p>You must add employees before you can set and view
            their times.</p>
        </div>
        <div>
            <p>Click on the 'add employee' tab</p>
            <p>Enter the first and last name of the employee. Note that
            both fields are required</p>
            <p>When an employee is added, a confirmation message will
            appear.</p>
        </div>
        <div>
            <h2>Viewing Available Bookings</h2>
        </div>
        <div>
            <p>On the mainpage, scroll to the employee you would 
            like to see</p>
            <p>Employees you have set booking times to will appear.</p>
            <p>Select by clicking on the time</p>
        </div>
        <div>
            <h2>Adjusting Worker Availability</h2>
        </div>
        <div>
            <p>Click on the 'employee times' tab </p>
            <p>Scroll to the staff member you would like to edit 
            working hours for</p>
        </div>
        <div>
            <h2>Combined Worker View</h2>
        </div>
        </div>
        <?php
    }
}
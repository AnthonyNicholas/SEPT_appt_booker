<?php
/**
 * OwnerHelpPage View
 * Holds all the HTML for the owner help pages
 * Authors: Dan
 */
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
            <img src="help-imgs/empinfo.PNG"  style="width:900px;height:200px;">
        </div>
        
        <div>
            <h2>Adding Employees</h2>
        </div>
        <div>
            <p>You must add employees before you can set and view
            their times. Employees may have specialisations that customers
            can select when they select an employee and appointment type.</p>
        </div>
        <div>
            <img src="help-imgs/add-emp.PNG" style="width:700px;height:90px;">
            <p>Click on the 'add employee' tab</p>
            <img src="help-imgs/add-emp22.PNG" style="width:900px;height:250px;">
            <p>Enter the first, last name of the employee and select an employee 
            skill. Note that both first and last name fields are required.
            An employee may have any number of skills.</p>
            <img src="help-imgs/add-emp33.PNG" style="width:900px;height:250px;">
            <p>Click 'Add Employee'. When an employee is added, a confirmation message
            will appear.</p>
        </div>
        
        <div>
            <h2>Adjusting Worker Availability</h2>
        </div>
        <div>
            <p>To view your employees' booking slots on the mainpage and the 
            combined employee view, times must be set for an employee's
            working hours.</p>
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
            <h2>Create a Booking for a Customer</h2>
        </div>
        <div>
            <p>Bookings can be made on behalf of the customer, provided that they
            are registered in the system. The customer's email is needed to book on
            their behalf.</p>
        </div>
        <div>
            <img src="help-imgs/how3makebook.PNG" style="width:900px;height:210px;">
            <p>Select an appointment type and an employee</p>
            <img src="help-imgs/how3makebook1.PNG" style="width:900px;height:300px;">
            <p>Select a time by clicking on an available slot</p>
            <img src="help-imgs/bookascustomer.PNG" style="width:900px;height:240px;">
            <p>Enter a valid customer email. Note that if a customer cannot be found
            an error message will appear.</p>
            <img src="help-imgs/bookascustomer2.PNG" style="width:900px;height:210px;">
            <p>A confirmation message will appear if successful.</p>
            
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
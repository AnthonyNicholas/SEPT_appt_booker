<?php

class OwnerMainPageView    {
    
    public function printHtml($fName = "Sample", $lName = "Owner")    {
    ?>
    <div class = "jumbotron jumbotron-fluid">
        <?php echo "Welcome, ".$fName." ".$lName; ?>
    </div>
        <table>
            <tr><th>Item</th></tr>
            <tr><td><a href="owner-bookings.php">Edit Bookings</a></td></tr>
            <tr><td><a href="edit-details.php">Edit Your Details</a></td></tr>
            <tr><td><a href="logout.php">Logout</a></td></tr>
        </table>

    <div class="bookings"></div>
    <?php
        
    }
}
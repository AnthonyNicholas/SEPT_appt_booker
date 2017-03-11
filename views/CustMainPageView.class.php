<?php

class CustMainPageView    {
    
    public function printHtml($fName, $lName)    {
    ?>
    <div class="welcome-msg">
        <?php echo "Welcome, ".$fName." ".$lName; ?>
    </div>
    <div class="menu">
        <table class="menu-table">
            <tr><a href="cust-bookings.php">Your Bookings</a></tr>
            <tr><a href="edit-details.php">Edit Your Details</a></tr>
            <tr><a href="logout.php">Logout</a></tr>
        </table>
    </div>
    <div class="bookings">
        
    </div>
    <?php
    }
}
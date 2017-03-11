<?php

class CustMainPageView    {
    
    public function printHtml($fName = "Sample", $lName = "Customer")    {
    ?>
    <div class = "jumbotron jumbotron-fluid">
        <?php echo "Welcome, ".$fName." ".$lName; ?>
    </div>
    <!--<div class="menu">-->
        <table>
            <tr><th>Item</th></tr>
            <tr><td><a href="cust-bookings.php">Your Bookings</a></td></tr>
            <tr><td><a href="edit-details.php">Edit Your Details</a></td></tr>
            <tr><td><a href="logout.php">Logout</a></td></tr>
        </table>
    <!--</div>-->
    <!--<div class="bookings">-->
    <!--</div>-->

    <?php
    }
}
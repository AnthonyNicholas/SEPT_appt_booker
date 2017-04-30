<?php

// Shows the customer a simple list of any bookings they have upcoming

class BookingSummary
{
    public function printHtml($customer)
    {
        $bookings = $customer->get_bookings();
        
        echo "<h4 class = \"list-group-item-heading\"> My Bookings </h4>";
        
        for ($i = 0; $i < count($bookings); $i++)
        {
            $empId = $bookings[$i]->get_empId();
            $date_time = $bookings[$i]->get_dateTime();
            $fname = $bookings[$i]->get_fname();
            $lname = $bookings[$i]->get_lname();
            $type = $bookings[$i]->get_type();

            $date = $date_time->format("d-m-Y");
            $time = $date_time->format("H:i:s");
                
            echo "<div class = \"list-group-item\">".$date."&nbsp;&nbsp;&nbsp;".$time."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$fname." ".$lname."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".$type."</div>";  
        }
    }
}
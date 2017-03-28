<?php

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

            $date = $date_time->format("d-m-Y");
            $time = $date_time->format("H:i:s");
                
            echo "<div class = \"list-group-item\">".$date."   ".$time." with ".$fname." ".$lname."</div>";  
        }
    }
}
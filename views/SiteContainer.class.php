<?php
/**
 * This class includes headers, footers, navigation and any other
 * styling/backend content that is required
 */

class SiteContainer
{
    
    public $title = "Booking System";

    public function printHeader()
    {
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->title; ?></title>
    </head>
    <?php
    }

    public function printFooter()
    {



    }

}

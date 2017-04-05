<?php
/**
 * This page handles ajax request for calendar appointments. Ajax request is generated by bootstrap-calendar.js.
 *
  */

    require_once('Controller.class.php');

    $ctrl = new Controller();

    // call the method in Controller class to retrieve appointment details from DB.
    if (isset($_GET['calendar_id']))
    {
        $empid = $_GET['calendar_id'];
        $ts = $_GET['timestamp'];
        $dt = new DateTime();
        $dt->setTimestamp($ts);
        $ctrl->bookingViewByOwner($empid, $dt);
        
    }

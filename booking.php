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
        $apptypeid = isset($_POST['apptype']) ? $_POST['apptype']:'';
        
        $dt = new DateTime();
        $dt->setTimestamp($ts);

        // Check if we are actually making a booking or just confirming a selection
        if ( empty($_POST) && $_SESSION['type'] == 'customer')
        {
            $ctrl->bookingConfirm($empid, $dt, $apptypeid);

        } else if (empty($_POST) && $_SESSION['type'] == 'owner')
        {
            // If owner and post is NOT set, we are viewing an existing booking
            $ctrl->bookingView($empid, $dt);
        } else if (!empty($_POST) && $_SESSION['type'] == 'owner')
        {
            // If POST is set, we are probably trying to make a booking as an owner
            $empID = $_POST['empID'];
            $custEmail = $_POST['custEmail'];
            // $ctrl->bookingCreate($empID, $dt, $custEmail, $apptypeid);
        } else if ($_POST['a'] == 'create')
        {
            $custEmail = $_SESSION['email'];
            $ctrl->bookingCreate($empid, $dt, $custEmail, $apptypeid);
        }
    }

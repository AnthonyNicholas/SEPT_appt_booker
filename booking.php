<?php
/**
 * booking Frontend
 * Used as a point of entry to the application Controller
 * Handles all references to an individual appointment
 */

    require_once('Controller.class.php');

    $ctrl = new Controller();

    // call the method in Controller class to retrieve appointment details from DB.
    if (!isset($_GET['calendar_id']) || !isset($_GET['timestamp']))
    {
        $ctrl->err_page();
        die();
    }

    $empid = $_GET['calendar_id'];
    $ts = $_GET['timestamp'];
    $apptypeid = isset($_GET['apptype']) ? $_GET['apptype']:'';
    // Override apptypeid if POST is set
    $apptypeid = isset($_POST['apptype']) ? $_POST['apptype'] : $apptypeid;
    
    $dt = new DateTime();
    $dt->setTimestamp($ts);

    // Check if we are actually making a booking or just confirming a selection
    if ($_SESSION['type'] == 'customer')
    {
        if (empty($_POST))
        {
            $ctrl->bookingConfirm($empid, $dt, $apptypeid);
        } else
        {
            $custEmail = $_SESSION['email'];
            $ctrl->bookingCreate($empid, $dt, $custEmail, $apptypeid);
        }

    } else if ($_SESSION['type'] == 'owner')
    {
        // If owner and post is NOT set
        if (empty($_POST))
        {
            $ctrl->bookingOwner($empid, $dt, $apptypeid);
        } else if (!empty($_POST))
        {
            // If POST is set, we are probably trying to make a booking as an owner
            $custEmail = $_POST['custEmail'];
            $ctrl->bookingCreate($empid, $dt, $custEmail, $apptypeid);
        }
    }

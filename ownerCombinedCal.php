<?php
/**
 * ownerCombinedCal Frontend
 * Used as a point of entry to the application Controller
 * This is the combined calendar view for owners. 
 */

require_once('Controller.class.php');

$ctrl = new Controller();

// Check to make sure user is logged in:
// if ( empty($_POST['email']) )
// {
//     // Call the login form controller method
//     $ctrl->loginForm();
// } else
// {
    // call the mainPageCust method
    $ctrl->ownerCombinedCal();
// }


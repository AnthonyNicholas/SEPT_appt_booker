<?php
/**
 * This is the mainpage for customers. Even though this is the file 
 * that users will navigate to in their browser, this should act more as a portal to the
 * methods in the main Controller.class.php file.
 *
 * No logic to be put in these files!
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
    $ctrl->mainPageCust();
// }


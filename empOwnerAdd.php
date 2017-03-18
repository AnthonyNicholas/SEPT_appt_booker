<?php
/**

 */

require_once('Controller.class.php');

$ctrl = new Controller();

if ($ctrl->ownerLoggedIn()) {
    
    $ctrl->addEmpOwner($_POST['lname'], $_POST['fname']);
}


// Check to make sure user is logged in:
// if ( empty($_POST['email']) )
// {
//     // Call the login form controller method
//     $ctrl->loginForm();
// } else
// {
    // call the mainPageCust method
   
// }


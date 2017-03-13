<?php
/**
 * This will be the point of entry for users of the system.
 * Even though this is the file that users will navigate to
 * in their browser, this should act more as a portal to the
 * methods in the main Controller.class.php file.
 *
 * Feel free to copy this file to any page that will be front
 * facing. The only thing that should change is the filename and
 * the method this function calls from the Controller class
 *
 * No logic to be put in these files!
 */

require_once('Controller.class.php');

$ctrl = new Controller();

// I think I'm breaking the logic rule here...
if ( empty($_POST['email']) )
{
    // Call the login form controller method
    $ctrl->loginForm();
} else
{
    // call the login controller method and prevent SQL injection
    $ctrl->login($_POST['email'], $_POST['pword']);
}

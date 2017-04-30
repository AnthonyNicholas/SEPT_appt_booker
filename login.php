<?php
/**
 * This will be the point of entry for users of the system.
 * Even though this is the file that users will navigate to
 * in their browser, this should act more as a portal to the
 * methods in the main Controller.class.php file.
 * login Frontend
 * Used as a point of entry to the application Controller
 */
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

<?php
// the registration form submits to itself
// so this page handles both before and after the form is submitted
// for now if registration fails, the errors are being sent back with the form via GET variables
// as yet it still won't display the error message, not sure if we want a new view for that or something else

require_once('Controller.class.php');

$controller = new Controller();

if (empty($_POST))
    $controller->registerFormCust($_GET['error']); 
else
    $controller->registerCust($_POST['email'], $_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['phone'], $_POST['pword'], $_POST['pword2']);
?>

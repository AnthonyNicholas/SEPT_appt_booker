<?php
// Registration form which submits to itself
// handles both before and after the form is submitted
// If registration fails, the errors are being sent back with the form via GET variables
// and delegated to FormError view

require_once('Controller.class.php');

$controller = new Controller();

if (empty($_POST))
    $controller->registerFormCust(); 
else
    $controller->registerCust($_POST['email'], $_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['phone'], $_POST['pword'], $_POST['pword2']);
?>

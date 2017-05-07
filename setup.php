<?php


require_once('Controller.class.php');

$controller = new Controller();

// should be login protected


if (empty($_POST))
    $controller->setupForm(); 
else
    $controller->setup($_POST);
?>





<?php


require_once('Controller.class.php');

$setup = true;
$controller = new Controller();

// should be login protected


if (empty($_POST))
    $controller->switchView();
else
    $controller->do_switch($_POST);


?>




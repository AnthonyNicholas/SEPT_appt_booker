<?php

require_once('Controller.class.php');

$controller = new Controller();

if (!isset($_POST))
    header('Location: WorkerAvailability.php');
else
    $controller->add_working_times($_POST);
    



 // echo '<pre>'; print_r($_POST); echo '</pre>';




<?php

// Front end handler for sending employee shift times to the database

require_once('Controller.class.php');

$controller = new Controller();

if (!isset($_POST))
    header('Location: WorkerAvailability.php');
else
    if (!$controller->add_working_times($_POST))
        header('Location: WorkerAvailability.php?error=bad_time');



<?php
/**
 * WorkerAvail Frontend
 * Used as a point of entry to the application Controller
 */
// Show the business owner the availability of each of their employees
// and give the option to add more times

require_once('Controller.class.php');

$controller = new Controller();
 echo "<script src=\"js/main.js\"></script>"; 
$controller->show_worker_availability();
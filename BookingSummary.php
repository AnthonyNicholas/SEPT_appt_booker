<?php

require_once('models/Customer.class.php');
require_once('views/BookingSummary.class.php'); 
require_once('Controller.class.php');

$controller = new Controller();
$controller->view_booking_summary();


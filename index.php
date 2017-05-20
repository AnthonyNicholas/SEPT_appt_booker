<?php
/**
 * SEPT_Appointment_Booker Application
 * Authors: Adam, Jake, Anthony, Dan
 * This file checks who is logged in, if anyone, and redirects them to the appropriate page
 */
require_once('Controller.class.php');

$ctrl = new Controller();

// check first time user

$ctrl->index();

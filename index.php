<?php
/**
 * This file checks who is logged in, if anyone, and redirects them to the appropriate page
 */
require_once('Controller.class.php');

$ctrl = new Controller();

$ctrl->index();

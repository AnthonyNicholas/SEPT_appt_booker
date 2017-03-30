<?php
/**
 * Configuration Variables for our site, things that will likely change
 * server to server should go here
 */

$config = array(
    'site_url' => '',
    'site_path' => '',
    'db_addr' => 'localhost',
    'db_user' => 'antfellow',
    'db_pass' => '',
    'db_name' => 'appt_booker',
    'debug' => true
);

define("BOOSTRAP_JS", "calendar/bootstrap/js/");
define("BOOTSTRAP_CSS", "calendar/bootstrap/css/");
define("CSS","css/");
define("JS","js/");
define("HOST_URL","https://localhost/");

//set default timezone to maintain proper event calculations
date_default_timezone_set("Australia/Melbourne");

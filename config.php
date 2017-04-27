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

define("CSS","css/");
define("JS","js/");
define("SLOT_LEN", 30);

//set default timezone to maintain proper event calculations
date_default_timezone_set("Australia/Melbourne");

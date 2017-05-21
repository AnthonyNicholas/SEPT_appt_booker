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
    'db_name' => 'NO_DATABASE', // <-- choose which instance of the site to use, 'NO_dATABASE' will result in new business setup
    'log_file' => 'appointments.log',
    'debug' => false,
    'admin' => 'owner@email.com',
    'pass' => 'pw'
);
 
define("CSS","css/");
define("JS","js/");
define("SLOT_LEN", 30);

//set default timezone to maintain proper event calculations
date_default_timezone_set("Australia/Melbourne");

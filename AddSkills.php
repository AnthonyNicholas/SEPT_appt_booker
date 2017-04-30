<?php
/**
 * AddSkills Frontend
 * Used as a point of entry to the application Controller
 */

require_once('Controller.class.php');

$controller = new Controller();

if (!isset($_POST['skills']))
    $controller->add_skills_page(false);
else 
{
    $controller->add_employee_skills($_POST['skills'], $_POST['employee']);
    // add success message
    $controller->add_skills_page(true);  
}


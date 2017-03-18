<?php
/**

 */

require_once('Controller.class.php');

$ctrl = new Controller();

if ($ctrl->ownerLoggedIn())    {
    
    $ctrl->addEmpFormOwner();
    
    if (isset($_POST['fname']))    {
        $ctrl->addEmpOwner($_POST['fname'], $_POST['lname']);
    }
    
}
else    {
    $ctrl->restricted();
}


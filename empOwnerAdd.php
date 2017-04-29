<?php
/**

 */

require_once('Controller.class.php');

$ctrl = new Controller();

if ($ctrl->ownerLoggedIn())    {
    
    if (isset($_POST['fname']))    {
        
        if ($ctrl->addEmpOwner($_POST['fname'], $_POST['lname'], $_POST['skills'])) 
        {
            $ctrl->addEmpFormOwner(true);
        }
    }
    $ctrl->addEmpFormOwner(false); // this confusing, flow should be fixed at a later date
    
}
else    {
    $ctrl->restricted();
}


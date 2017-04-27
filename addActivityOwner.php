<?php
/**

 */

require_once('Controller.class.php');

$ctrl = new Controller();

if ($ctrl->ownerLoggedIn())    {
    
    if (isset($_POST['fname']))    {
        if ($ctrl->addActivityOwner($_POST['fname'], $_POST['lname'])) {
            $ctrl->addActivityFormOwner(true);
        }
    }
    $ctrl->addActivityFormOwner(false); // this confusing, flow should be fixed at a later date
    
}
else    {
    $ctrl->restricted();
}


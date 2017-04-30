<?php
/**
 * addActivityOwner Frontend
 * Used as a point of entry to the application Controller
 */

require_once('Controller.class.php');

$ctrl = new Controller();

if ($ctrl->ownerLoggedIn())    {
    
    if (isset($_POST['appType']))    {
        
        if ($ctrl->addActivityOwner($_POST['appType'], $_POST['appDesc'], $_POST['appDuration'])) {
            $ctrl->addActivityFormOwner(true);
        }
    }
    $ctrl->addActivityFormOwner(false); // this confusing, flow should be fixed at a later date
    
}
else    {
    $ctrl->restricted();
}


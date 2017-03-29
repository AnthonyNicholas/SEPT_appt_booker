<?php
/**

 */

require_once('Controller.class.php');

$added = false;
$ctrl = new Controller();

if ($ctrl->ownerLoggedIn())    {
    
    $ctrl->addEmpFormOwner($added);
    
    if (isset($_POST['fname']))    {
        if ($ctrl->addEmpOwner($_POST['fname'], $_POST['lname']))    {
            $added = true;
            $ctrl->addEmpFormOwner($added);
        }
    }
    
}
else    {
    $ctrl->restricted();
}


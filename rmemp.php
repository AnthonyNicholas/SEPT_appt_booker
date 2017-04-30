<?php
require_once('Controller.class.php');
$ctrl = new Controller();
// $er = array(80, 81,82,83,84,85);
$er = array();

foreach($er as $e){
    $ctrl->deleteEmployee($e);
}

for( $i=87;$i<=139;$i++)
    $ctrl->deleteEmployee($i);
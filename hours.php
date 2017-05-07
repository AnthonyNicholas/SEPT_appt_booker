<?php


require_once('Controller.class.php');

$controller = new Controller();

// should be login protected


if (empty($_POST))
    $controller->hoursForm("blank"); 
else{
    if (!$controller->set_hours($_POST['start'], $_POST['end']))
        $controller->hoursForm("bad_time");
    else
        $controller->hoursForm("success");
   // echo '<pre>'; print_r($_POST); echo '</pre>';
}

?>





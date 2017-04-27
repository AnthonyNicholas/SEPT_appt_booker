<?php
/**

 */

require_once('Controller.class.php');

$ctrl = new Controller();

$ctrl->searchCustomerView();

if (isset($_POST['email']))   {
   if ($ctrl->bookAsCustomer($_POST['email']) === true)   {
      $ctrl->bookAsCustomerView();
   }
}




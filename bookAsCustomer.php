<?php
/**

 */

require_once('Controller.class.php');

$ctrl = new Controller();

if (isset($_POST['custEmail']))   {
   if ($cust = $ctrl->bookAsCustomer($_POST['custEmail']))   {
      $ctrl->bookAsCustomerView($cust);
   }
   else {
      $this->redirect("bookAsCustomer.php?error=custNotFound");
   }
} else
{
      $ctrl->bookAsCustomerView();
}




<?php

/** Handles the printing of errors
 *  Usually the error is printed above a form
 *  if it was submitted with invalid data
 */
  
class FormError
{
    private $error = array(
                           "email" => "Did not enter a valid email",
                           "fname" => "Did not enter a valid first name",
                           "lname" => "Did not enter a valid last name",
                           "password" => "Passwords do not match",
                           "duplicate" => "An account with that email address already exists",
                           "err_login_failed" => "Invalid username or password - please try again");

    public function printHtml($errors) // take the list of errors and print relevant messages
    {
         echo "<div class = \"error_list\">";

         for ($i = 0; $i < count($errors); $i++) 
             echo "<div class = \"error_msg\"><li>".$this->error[$errors[$i]]."</li></div>"; 

         echo "</div>";
    }
}

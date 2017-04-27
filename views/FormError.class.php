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
                           "pstrength" => "Passwords must be at least 6 characters long, and contain at least one of each: uppercase letter, lowercase letter, digit, and special char (e.g. !@#$%)",
                           "duplicate" => "An account with that email address already exists",
                           "err_login_failed" => "Invalid username or password - please try again",
                           "login_required" => "Please log in to access restricted content",
                           "bad_time" => "Please enter valid times only",
                           "appType" => "Please re-enter name of new appointment. Name must consist of alphanumeric characters only.",
                           "bad_apptype" => "Please select appointment type",
                           "appDesc" => "Please enter valid times only");


    public function printHtml($errors) // take the list of errors and print relevant messages
    {
         echo "<div class = \"error_list\">";

         for ($i = 0; $i < count($errors); $i++) 
             echo "<div class = \"text-danger\"><h3>".$this->error[$errors[$i]]."</h3></br></div>"; 

         echo "</div>";
    }
}

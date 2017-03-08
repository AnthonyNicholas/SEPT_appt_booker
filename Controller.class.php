<?php
/**
 * Controller Class
 *
 * Shell for the main controller class, this could be static and used
 * as a library of utilities for our web application or it could be
 * used as an object for keeping information about the current session
 * 
 */
class Controller
{

    public $debug = true;

    public function loginForm()
    {
        // here the login form view class is loaded and method printHtml() is called    

    }

    public function login($email, $password)
    {
        // here login data will be validated and processed, and user data saved into the PHP session


        // maybe a header redirect here to the main page?

    }

    // Handles the display of the main page for customers
    public function mainPageCust()
    {


    }

    // Handles the display of the main page for owners
    public function mainPageOwner()
    {


    }


    // displays the register form for customers
    public function registerFormCust()
    {


    }

    // validate and enter the register information into the database
    // will need to check for duplicate users/email already in use
    public function registerCust()
    {


    }

    // displays the register form for owners
    public function registerFormOwner()
    {


    }

    // validates and enter register information into database
    public function registerOwner()
    {


    }

    // Main management page for manages
    public function managementPageOwner()
    {


    }

    // displays the form for adding employees
    // possibly lists all current employees too?
    public function addEmpFormOwner()
    {



    }

    // processes and adds entered employee into the database
    public function addEmpOwner()
    {


    }

}

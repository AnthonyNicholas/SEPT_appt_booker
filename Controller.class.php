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

    public $config;
    private $db;

    public function __construct()
    {
        // fetch our configuration
        require_once('config.php');
        $this->config = $config;

        // Set up our database object for use within the controller
        $this->db = new mysqli(
            $config['db_addr'],
            $config['db_user'],
            $config['db_pass'],
            $config['db_name']
        );

        if ($this->db->connect_errno)
        {
            $errmsg = $config['debug'] ? ": " . $this->db->connect_error : ", errors may follow.";
            echo "Failed to connect to MySQL" . $errmsg . "<br/>\n";
        }

    }

    public function loginForm()
    {
        // here the login form view class is loaded and method printHtml() is called    
        require_once('views/SiteContainer.class.php');
        require_once('views/LoginForm.class.php');
        $site = new SiteContainer();
        $form = new LoginForm();

        $site->printHeader();
        $form->printHtml();
        $site->printFooter();
        

    }

    public function login($email, $password)
    {
        // here login data will be validated and processed, and user data
        // saved into the PHP session
        // This is very succeptible to SQL injection - ENSURE DATA IS CLEAN
        $res = $this->db->query("SELECT email, fName, lName FROM Customers WHERE email = '$email' AND password = '$password';");
        $user = $res->fetch_assoc();


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

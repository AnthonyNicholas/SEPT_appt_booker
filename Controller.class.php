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
        $site->printNav();
        $form->printHtml();
        $site->printFooter();
        

    }

    public function login($email, $password)
    {
        // here login data will be validated and processed, and user data
        // saved into the PHP session
        $stmt = $this->db->prepare("SELECT email, fName, lName FROM Customers WHERE email = ? AND password = ?;");
        // Insert our given username and password into the statement safely
        $stmt->bind_param('ss', $email, $password);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc())
        {
            // Login successful
            session_start();
            $_SESSION['email'] = $user['email'];
            $this->redirect("/");
        } else
        {
            $this->redirect("/login.php");
            echo "err_login_failed";
        }

    }

    // Handles the display of the main page for customers
    public function mainPageCust()
    {
        // here the login form view class is loaded and method printHtml() is called    
        require_once('views/SiteContainer.class.php');
        require_once('views/CustMainPageView.class.php');
        $site = new SiteContainer();
        $page = new CustMainPageView();

        $site->printHeader();
        $site->printNav("customer");
        $page->printHtml();
        $site->printFooter();

    }

    // Handles the display of the main page for owners
    public function mainPageOwner()
    {
        require_once('views/SiteContainer.class.php');
        require_once('views/OwnerMainPageView.class.php');
        $site = new SiteContainer();
        $page = new OwnerMainPageView();

        $site->printHeader();
        $site->printNav("owner");
        $page->printHtml();
        $site->printFooter();

    }


    // displays the register form for customers
    public function registerFormCust()
    {
        require_once('views/SiteContainer.class.php');
        require_once('views/RegistrationForm.class.php');
        $site = new SiteContainer();
        $form = new RegistrationForm();
        $site->printHeader();
        $site->printNav("customer");
        $form->printHtml();
        $site->printFooter();
    }

    // validate and enter the register information into the database
    // will need to check for duplicate users/email already in use
    public function registerCust($email, $fname, $lname, $address, $phone, $pword, $pword2)
    {
        $errors = array(); // list of errors
     
        if (strcmp($pword, $pword2)) // check passwords match
            $errors[] = 'password';
  
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) // validate email
            $errors[] = 'email';
    
        if (!preg_match("/^[a-zA-Z'-]+$/",$fname)) // match first name to sensible regex
            $errors[] = 'fname';
    
        if (!preg_match("/^[a-zA-Z'-]+$/",$lname)) // match last name
            $errors[] = 'lname';
  
        // prepare statement for checking email
        $q = $this->db->prepare("SELECT * FROM Customers WHERE email = ?;");
        $q->bind_param('s', $email);
        $q->execute();
        $result = $q->get_result();

        if (mysqli_num_rows($result) > 0) // check email doesn't already exist
            $errors[] = 'duplicate';
  
        if (!empty($errors)) // if registration fails, back to form with errors
        {       
            $errors = implode(',', $errors);
            header("Location: register.php?error=".htmlspecialchars(urlencode($errors))); 
        }
        else  // if registration succeeds, log in
        { 
            // prepare statement for insert
            $q = $this->db->prepare("INSERT INTO Customers (email, fName, lName, address, phoneNo, password) VALUES  (?, ?, ?, ?, ?, ?);");
            $q->bind_param('ssssss', $email, $fname, $lname, $address, $phone, $pword);
            $q->execute();
            // $controller->login($email, $pword); // login after account is created, commented until login is fully implemented   
        } 
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

    // Handles PHP redirects
    public function redirect($page = '')
    {
        // If not running under apache (ie test case), dont attempt to redirect
        if ( isset($_SERVER['HTTP_HOST']) )
        {
            $site_url = empty($this->config['url']) ? $_SERVER['HTTP_HOST'] : $this->config['url'];
            header('Location: ' . $site_url . $page);
        }
    }

}

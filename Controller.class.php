<?php
/**
 * Controller Class
 *
 * Shell for the main controller class, this could be static and used
 * as a library of utilities for our web application or it could be
 * used as an object for keeping information about the current session
 * 
 */

require_once('views/SiteContainer.class.php');

class Controller
{

    public $config;
    private $db;
    public $user;

    public function __construct()
    {
        // fetch our configuration
        require_once('config.php');
        $this->config = $config;

        if ($config['debug'])
        {
            ini_set('display_startup_errors', 1);
            ini_set('display_errors', 1);
            error_reporting(-1);
        }

        // Start the session
        session_start();

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

    /**
     * handles requests for index.php
     */
    public function index()
    {
        if ($this->custLoggedIn())
        {
            $this->redirect("/mainPageCust.php");
        } elseif ($this->ownerLoggedIn())
        {
            $this->redirect("/mainPageOwner.php");
        } else
        {
            $this->redirect("/login.php");
        }

    }

    public function loginForm()
    {
        // If already logged in, redirect appropriately
        if ($this->custLoggedIn())
        {
            $this->redirect("/mainPageCust.php");
        } elseif ($this->ownerLoggedIn())
        {
            $this->redirect("/mainPageOwner.php");
        }
        
        // prepare errors to display if there are any
        $error = array();
        if (!empty($_GET['error']))
        {
            $error_string = urldecode($_GET['error']);
            $error = explode(',', $error_string);
        }
        
        // here the login form view class is loaded and method printHtml() is called  
        require_once('views/FormError.class.php');
        require_once('views/LoginForm.class.php');
        $site = new SiteContainer();
        $form = new LoginForm();
        $error_page = new FormError();
        $site->printHeader();
        $site->printNav();
        $error_page->printHtml($error);
        $form->printHtml();
        $site->printFooter();
        
    }

    public function login($email, $password)
    {
        // here login data will be validated and processed, and user data
        // saved into the PHP session
        // grab results from customer database
        $stmt_cust = $this->db->prepare("SELECT email FROM Customers WHERE email = ? AND password = ?;");
        // Insert our given username and password into the statement safely
        $stmt_cust->bind_param('ss', $email, $password);
        // Execute the query
        $stmt_cust->execute();
        // Fetch the result
        $res_cust = $stmt_cust->get_result();

        // Grab results from BusinessOwner
        $stmt_own = $this->db->prepare("SELECT email FROM BusinessOwner WHERE email = ? AND password = ?;");
        // Insert our given username and password into the statement safely
        $stmt_own->bind_param('ss', $email, $password);
        // Execute the query
        $stmt_own->execute();
        // Fetch the result
        $res_own = $stmt_own->get_result();

        if ($user = $res_cust->fetch_assoc())
        {
            // Login successful
            $_SESSION['email'] = $user['email'];
            $_SESSION['type'] = 'customer';
            $this->redirect("/");

        } elseif ($user = $res_own->fetch_assoc())
        {
            // Login of owner successful
            $_SESSION['email'] = $user['email'];
            $_SESSION['type'] = 'owner';
            $this->redirect("/");

        } else
        {
            $error = "err_login_failed";
            $this->redirect("/login.php?error=$error");
            echo $error;
        }

    }

    // Handles the display of the main page for customers
    public function mainPageCust()
    {
        // Restricted access
        if ( ! $this->custLoggedIn() )
            $this->redirect("/login.php?error=login_required");

        // here the login form view class is loaded and method printHtml() is called    
        require_once('views/CustMainPageView.class.php');
        $site = new SiteContainer();
        $page = new CustMainPageView();

        // Load the Customer model, because this is a customer page
        require_once('models/Customer.class.php');
        // Give the model the email address in the session and the database object
        try{
            $this->user = new Customer($_SESSION['email'], $this->db);
        } catch (Exception $e)
        {
            $this->redirect("/login.php?error=login_required");
            echo "err_user_not_found";
        }

        $site->printHeader();
        $site->printNav($this->user->type);
        $site->printFooter();
        // data is an object containing userdata exactly how it appears in the db
        $page->printHtml($this->user->data);
        $page->printCalendar();

    }

    // Handles the display of the main page for owners
    public function mainPageOwner()
    {
        // Restricted access
        if ( ! $this->ownerLoggedIn() )
        {
            $this->restricted();
            return;
        }

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
        $error = array();
        if (!empty($_GET['error']))
        {
            $error_string = urldecode($_GET['error']);
            $error = explode(',', $error_string);
        }

        require_once('views/FormError.class.php');
        require_once('views/RegistrationForm.class.php');
        $site = new SiteContainer();
        $error_page = new FormError();
        $form = new RegistrationForm();
        $site->printHeader();
        $site->printNav("customer");

        $error_page->printHtml($error);
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
            //$this->redirect("/register.php?error=".htmlspecialchars(urlencode($errors))"")
            header("Location: /register.php?error=".htmlspecialchars(urlencode($errors))); 
        }
        else  // if registration succeeds, log in
        { 
            // prepare statement for insert
            $q = $this->db->prepare("INSERT INTO Customers (email, fName, lName, address, phoneNo, password) VALUES  (?, ?, ?, ?, ?, ?);");
            $q->bind_param('ssssss', $email, $fname, $lname, $address, $phone, $pword);
            $q->execute();
            $this->login($email, $pword); // login after account is created, commented until login is fully implemented   
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

    // Very basic if logged in function
    public function custLoggedIn()
    {
        // For now just check session for an email and customer type
        if ( empty($_SESSION['email']) || $_SESSION['type'] != 'customer')
            return false;
        else
            return true;

    }
    // Owner logged in function
    public function ownerLoggedIn()
    {
        // For now just check session for an email and owner type
        if ( empty($_SESSION['email']) || $_SESSION['type'] != 'owner')
            return false;
        else
            return true;

    }

    public function restricted()
    {
        $site = new SiteContainer();

        $site->printHeader();
        $site->printNav($_SESSION);
        echo "You are not allowed to access this resource. Return <a href=\"/\">Home</a>";
        $site->printFooter();

    }
    public function logout()
    {
        session_unset();

        $site = new SiteContainer();

        $site->printHeader();
        $site->printNav();
        echo "Successfully logged out. Return to <a href=\"login.php\">login</a>";
        $site->printFooter();

    }
    // Handles PHP redirects
    public function redirect($page = '')
    {
        // If not running under apache (ie test case), dont attempt to redirect
        if ( isset($_SERVER['HTTP_HOST']) )
        {
            $site_url = empty($this->config['site_url']) ? '//'.$_SERVER['HTTP_HOST'] : $this->config['site_url'];
            header('Location: ' . $site_url . $page);
        }
    }

    public function workerAvailability() // parameter for how far to look ahead?
    {
        $employees = array();
        
        
        // load employees from database
        
        $q = $this->db->prepare("SELECT * FROM Employees E, CanWork C, Appointments A WHERE E.empID = C.empID AND C.appID = A.appID");
        $q->execute();
        $result = $q->get_result();
        
        while ($row = mysqli_fetch_array($result))
        {
            echo '<pre>'; print_r($row); echo '</pre>';
            
            // some epic processing here
        }
        
        
        
        require_once('views/WorkerAvailability.class.php');
        $site = new SiteContainer();
        $page = new WorkerAvailability();
        $site->printHeader();
        $site->printNav("owner");
        $page->printHtml($employees);
        $form->printHtml();
        $site->printFooter();
    }
}

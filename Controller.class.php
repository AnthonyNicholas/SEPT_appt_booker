<?php
/**
 * Controller Class
 *
 * Shell for the main controller class, this could be static and used
 * as a library of utilities for our web application or it could be
 * used as an object for keeping information about the current session
 * 
 */

define('MINIMUM_INTERVAL', 30);

require_once('views/SiteContainer.class.php');

class Controller
{

    public $config;
    private $db;
    public $user;
    
    public function get_db()
    {
        return $this->db;
    }

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

        $query = "SELECT fName, lName, empID
                FROM Employees;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        $empArray = $res->fetch_all(MYSQLI_ASSOC);

        $site->printHeader();
        $site->printNav($this->user->type);
        $site->printFooter();
        // data is an object containing userdata exactly how it appears in the db

        $page->printHtml($this->user->data);
        $page->printCalendar($empArray);

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
        
        $query = "SELECT fName, lName, empID
                FROM Employees;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        $empArray = $res->fetch_all(MYSQLI_ASSOC);

        $site->printHeader();
        $site->printNav("owner");
        $site->printFooter();
        $page->printHtml();
        $page->printCalendar($empArray);

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

        $testOutput = implode(" ", $errors);
        echo $testOutput; // have inserted for unit testing purposes
  
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
        if ( !$this->ownerLoggedIn() )
        {
            $this->restricted();
            return;
        }
        
        $error = array();
        if (!empty($_GET['error']))
        {
            $error_string = urldecode($_GET['error']);
            $error = explode(',', $error_string);
        }

        require_once('views/AddEmpOwner.class.php');
        require_once('views/FormError.class.php');
        $site = new SiteContainer();
        $page = new AddEmpOwner();
        $error_page = new FormError();

        $site->printHeader();
        $site->printNav("owner");
        $error_page->printHtml($error);
        $page->printHtml();
        $site->printFooter();   

    }

    // processes and adds entered employee into the database
    public function addEmpOwner($fname,$lname)
    {
        if (!preg_match("/^[a-zA-Z'-]+$/", $fname))    {
            $error = 'fname';
            header("Location: /empOwnerAdd.php?error=$error"); //Check first name
            return false;
        } 
        
        if (!preg_match("/^[a-zA-Z'-]+$/", $lname))    {
            $error = 'lname';
            header("Location: /empOwnerAdd.php?error=$error"); //Check last name
            return false;
        } 
        else    {
            $this->db->query("INSERT INTO Employees (empID, fName, lName)
            VALUES ('NULL', '$fname','$lname')"); //Insert new employee
             return true;
        }
        
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
    
    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////// ALL FUNCTIONS BELOW SHOULD BE CONSIDERED IN TESTING PHASE /////////////////
    
    public function add_working_times($times)
    {
        if (!isset($times['date']))
            return false;
        
        for ($i = 0; $i < count($times['date']); $i++)
        {   
            try {
                $start = new DateTime($times['date'][$i]." ".$times['start'][$i].":00");
                $end = new DateTime($times['date'][$i]." ".$times['end'][$i].":00");
                if (!$this->add_working_time($times['empID'], $start, $end))
                    return false;
            } catch (Exception $e) {
                // invalid date
                return false;
            }
        }
return true;

        header('Location: WorkerAvailability.php');
        
     //   return true;
    }
    
    public function workers_availability() // essentially load employees from database, return associative array including shifts
    {                                      // ready for use in html document
        $q = $this->db->prepare("SELECT empID, fName, lName FROM Employees;");
        
        if (!$q->execute())
            return false;
        
        $result = $q->get_result();
        
        $employees = array();
        
        $i = 0;
        
        while ($row = mysqli_fetch_assoc($result)) // set up associative array with relevant information for each employee
        {
            $employees[$i]['empID'] = $row['empID']; 
            $employees[$i]['fName'] = $row['fName']; 
            $employees[$i]['lName'] = $row['lName']; 
            $employees[$i]['shifts'] = $this->get_worker_hours($employees[$i]['empID']);
            
            $i++;
        }
        
       // echo '<pre>'; print_r($employees); echo '</pre>';
        
        return $employees;
    }
    
    public function get_worker_hours($empID) // return array of all stored shifts for given employee
    {
        require_once('models/Timeslot.class.php');
        
        $q = $this->db->prepare("SELECT A.dateTime FROM TimeSlot A, CanWork C WHERE A.dateTime = C.dateTime AND C.empID = '$empID'"); // grab all timeslots
       
        if (!$q->execute())
            return false;
        
        $result = $q->get_result();
        $timeslots = array();
        
        while ($row = mysqli_fetch_array($result)) // set up timeslots
        {
            $start = new DateTime($row['dateTime']);
            $end = new DateTime($row['dateTime']);
            $end->modify('+'.MINIMUM_INTERVAL.' minutes');
            
            $now = new DateTime();
            
            if ($now > $start) // check timeslot isn't in the past
                continue;

            $timeslots[] = new Timeslot($start, $end); 
        }
        
        $timeslots = $this->concatenate($timeslots); // convert into shifts
        
      //  echo '<pre>'; print_r($timeslots); echo '</pre>';
          $this->time_sort($timeslots);
        
    //    if ($timeslots == null)
      //      return false;
        
        return $timeslots;
    }
    
    public function add_appointments($interval, $start, $end) // add all appointments within given start and end time
    {
        while ($start <= $end)
        {
            $sd = $start->format("Y-m-d H:i:s");
            $q = $this->db->prepare("SELECT * FROM TimeSlot WHERE dateTime = ?;");
            $q->bind_param('s', $sd);
            
            if (!$q->execute())
                return false;
                
            $result = $q->get_result();
            
          //  echo $sd;

            if (mysqli_num_rows($result) == 0) // check appointment doesn't already exist
            {
                $q = $this->db->prepare("INSERT INTO TimeSlot (dateTime) VALUES ('$sd')");
                
                if (!$q->execute())
                    return false;
            }
            
            $start->modify('+'.$interval.' minutes');
        }
        
        return true;
    }
    
    public function add_working_time($empID, $start, $end) // associate employee to all appointments between $start and $end
    {
        while ($start < $end)
        {
            $sd = $start->format("Y-m-d H:i:s");
            $q = $this->db->prepare("SELECT dateTime FROM TimeSlot WHERE dateTime = ?;"); // check appointment exists
            $q->bind_param('s', $sd);
            
            if (!$q->execute())
                return false;
                
            $result = $q->get_result();

            if (mysqli_num_rows($result) > 0) 
            {
                $cw = 1;
                $row = mysqli_fetch_array($result); 
                
                $q = $this->db->prepare("SELECT dateTime, empID FROM CanWork WHERE empID = ? AND dateTime = ?;"); // check CanWork not already updated
                $q->bind_param('ss', $empID, $row['dateTime']);
                
                if (!$q->execute())
                    return false;
                    
                $result = $q->get_result();
                
                if (mysqli_num_rows($result) == 0) 
                {
                    $q = $this->db->prepare("INSERT INTO CanWork (empID, dateTime) VALUES (?, ?)");
                    $q->bind_param('ss', $empID, $row['dateTime']);
                    
                    if (!$q->execute())
                        return false;
                }
                
                else return false; // overlaps not accepted
            }
            else 
            {
                echo "failed <br>";
                // exception
                return false;
            }
            
            $start->modify('+'.MINIMUM_INTERVAL.' minutes');
        }
        
        return true;
    }
                                            
    public function concatenate($ts) // takes a list of timeslots,
    {                                // convert them into the minimum
        $n = count($ts);             // number of timeslots which capture
                                     // the same periods. 
        for ($k = 0; $k < $n; $k++)  // aka appointments -> shifts
        {     
            for ($i = 0; $i < count($ts) - 1; $i++)
            {
                for ($j = $i+1; $j < count($ts); $j++)
                {
                    if ($ts[$i]->get_end() >= $ts[$j]->get_start() && $ts[$i]->get_start() <= $ts[$j]->get_end())
                    {
                        $ts[$i] = new Timeslot(min($ts[$i]->get_start(), $ts[$j]->get_start()), max($ts[$j]->get_end(), $ts[$i]->get_end()));
                        unset($ts[$j]);
                        $ts = array_values($ts); 
                    }
                }
            }
        }     
        
        return $ts;
    }
    
    public function time_sort($timeslots) // sort timeslots into acending order
    {
        usort($timeslots, function ($t1,$t2) 
        {
           if ($t1->get_start()==$t2->get_start()) 
                return 0;
            
            return ($t1->get_start()<$t1->get_start())?-1:1;
        });
    }

    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * Should be called by an ajax request for employee calendar HTML, not fully implemented
     * still needs to deal with booking links etc
     */
    public function getCustCal($empNo, $weeks, $caltype = '')
    {
        require_once('models/Calendar.class.php');

        $cal = new Calendar($this->db);
        try{
            // Attempt to generate the calendar
            if ( $json_cal = $cal->ajaxGetCustCal($empNo, $weeks) )  // Successful, send calendar
                echo json_encode(array("success"=>true,"content"=>$json_cal));
            else
                throw new Exception("Failed to render Calendar");
                
        }catch(Exception $e){
          echo json_encode(array("success"=>false,"content"=>array(), "error"=>$e->getMessage()));
          return false;
        }

    }
    
    /**
     * Confirms that a user would like to book an appointment with employee and time
     */
    public function bookingConfirm($empId, $timestamp)
    {
        require_once('models/Calendar.class.php');
        require_once('models/Booking.class.php');
        require_once('views/BookingView.class.php');
        

        $site = new SiteContainer();
        $cal = new Calendar($this->db);
        $bk = new Booking($empId, $timestamp, $this->db);
        $bkv = new BookingView();
        
        try{
            // Attempt to generate the calendar
            $site->printHeader();
            $site->printNav("cust");
            $bkv->printConfirm($bk->d);
            $site->printFooter();   
                
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
    
    /**
     * books an appointment with employee and time, needs lots of error checking and fallbacks implemented
     * TODO
     */
    public function bookingCreate($empID, $timestamp)
    {
        require_once('models/Calendar.class.php');
        require_once('models/Booking.class.php');
        require_once('views/BookingView.class.php');
        

        $site = new SiteContainer();
        $cal = new Calendar($this->db);
        $bk = new Booking($empID, $timestamp, $this->db);
        $bkv = new BookingView();
        
        // Here we want to insert the booking, possibly check that it hasnt already been
        // taken, but not doing now TODO
        $sql = "INSERT INTO Bookings (empID, email, datetime) VALUES (?,?,FROM_UNIXTIME(?+3600));";
        // BAD PLEASE FIX TODO
        $stmt = $this->db->prepare($sql);
        // Insert our given username into the statement safely
        $stmt->bind_param('sss', $empID, $_SESSION['email'], $timestamp);
        
        
        $site->printHeader();
        $site->printNav("cust");
        
        if( $stmt->execute() ) // Our appointment was successfully booked
            $bkv->printSuccess($bk->d);
        else
            $bkv->printError();

        $site->printFooter();   

        //return $res->fetch_object();
        
    }
    
    // handles fetching the view for an owner to view employees' working times
    public function show_worker_availability()
    {
        $employees = $this->workers_availability();
        
        require_once('views/WorkerAvailability.class.php');
        $site = new SiteContainer();
        $page = new WorkerAvailability();

        $site->printHeader();
        $site->printNav("owner");
        $page->printHtml($employees);
        $site->printFooter();   

    }

    // handles fetching the view for a customer to view their bookings
    public function view_booking_summary()
    {
        // check logged in
        if ( ! $this->custLoggedIn() )
            $this->redirect("/login.php?error=login_required");
            
        require_once('models/Customer.class.php');
        require_once('views/BookingSummary.class.php'); 
        
        $site = new SiteContainer();

        $customer = new Customer($_SESSION['email'], $this->get_db());
        
        $bs = new BookingSummary();

        $site->printHeader();
        $site->printNav("customer");
        $bs->printHtml($customer);
        $site->printFooter(); 
    }
}

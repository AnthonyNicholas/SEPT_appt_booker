<?php
/**
 * Controller Class
 *
 * Shell for the main controller class, this could be static and used
 * as a library of utilities for our web application or it could be
 * used as an object for keeping information about the current session
 * 
 */

define('MINIMUM_INTERVAL', 30); // smallest timeslot duration

require_once('views/SiteContainer.class.php');
require_once('libs/Helper.php');

class Controller
{

    public $config;
    private $db;
    public $user;
    
    public function get_db()
    {
        return $this->db;
    }
    
    /**
     * Log to file method
     * Used to record the time and effect of user and admin events on the site
     * Authors: Adam
     */
    public function writeLog($msg)
    {
        $logline = PHP_EOL . "[" . date('r') . "]: " . $msg;
        file_put_contents($this->config['log_file'], $logline, FILE_APPEND);
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
     * Authors: Adam Young
     */
    public function index()
    {
        if ($this->custLoggedIn())
        {
            $this->redirect("mainPageCust.php");
        } elseif ($this->ownerLoggedIn())
        {
            $this->redirect("mainPageOwner.php");
        } else
        {
            $this->redirect("login.php");
        }

    }

    /**
     * Provides a login form for both users and owner
     * Authors: Adam Young
     */
    public function loginForm()
    {
        // If already logged in, redirect appropriately
        if ($this->custLoggedIn())
        {
            $this->redirect("mainPageCust.php");
        } elseif ($this->ownerLoggedIn())
        {
            $this->redirect("mainPageOwner.php");
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

    /**
     * Processes login for both users and owner
     * Authors: Adam Young
     */
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
            $this->redirect("index.php");

        } elseif ($user = $res_own->fetch_assoc())
        {
            // Login of owner successful
            $_SESSION['email'] = $user['email'];
            $_SESSION['type'] = 'owner';
            $this->redirect("index.php");

        } else
        {
            $error = "err_login_failed";
            $this->redirect("login.php?error=$error");
            echo $error;
        }

    }

    // Handles the display of the main page for customers
    // Authors: Adam, Anthony
    public function mainPageCust()
    {
        // Restricted access
        if ( ! $this->custLoggedIn() )
            $this->redirect("login.php?error=login_required");

        // here the login form view class is loaded and method printHtml() is called    
        require_once('views/CustMainPageView.class.php');
        
        $site = new SiteContainer();
        $page = new CustMainPageView();

        // Load the Customer model, because this is a customer page & AppType which is needed to retrieve correct calendar
        require_once('models/Customer.class.php');
        require_once('models/AppType.class.php'); 
        
        // Give the model the email address in the session and the database object
        try{
            $this->user = new Customer($_SESSION['email'], $this->db);
        } catch (Exception $e)
        {
            $this->redirect("login.php?error=login_required");
            echo "err_user_not_found";
        }

        $query = "SELECT fName, lName, empID
                FROM Employees;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        $empArray = $res->fetch_all(MYSQLI_ASSOC);
        // Fetch each employees associated types 
        $empTypes = array();
        foreach ($empArray as $e)
        {
            $tarr = array();
            $etypes = AppType::get_types_for_employee($e['empID'], $this->db);
            foreach ($etypes as $t)
                array_push($tarr, $t->get_appType());
            $empTypes[$e['empID']] = $tarr;
        }
        
        $types = AppType::get_all_types($this->db);
        $site->printHeader();
        $site->printNav($this->user->type);
        $page->printHtml($this->user, $types, $empArray);
        $page->printCalendar($empArray, $empTypes);
        $site->printSpecialFooter('calendar.js','calendarByType.js');

    }

    // Handles the display of the main page for owners
    // Authors: Adam, Anthony
    public function mainPageOwner()
    {
        // Restricted access
        if ( ! $this->ownerLoggedIn() )
        {
            $this->restricted();
            return;
        }

        require_once('models/BusinessOwner.class.php');
        $bo = new BusinessOwner($_SESSION['email'], $this->db);

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
        $page->printHtml($bo);
        $page->printCalendar($empArray); // to show combined view - change $empArray to just one entry with $empID = -1?
        $site->printFooter();
    }

    // Handles the display of the combined calendar page for owners
    // Authors: Anthony
    public function ownerCombinedCal()
    {
        // Restricted access
        if ( ! $this->ownerLoggedIn() )
        {
            $this->restricted();
            return;
        }

        require_once('views/OwnerCombinedCalView.class.php');
        $site = new SiteContainer();
        $page = new OwnerCombinedCalView();
        
        $site->printHeader();
        $site->printNav("owner");
        $site->printCombinedCalFooter();
        $page->printHtml();
        $page->printCalendar(); 

    }


    // displays the register form for customers
    // Authors: Dan
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
        $site->printNav("reg");

        $error_page->printHtml($error);
        $form->printHtml();
        $site->printFooter();
    }

    // validate and enter the register information into the database
    // checks for duplicate users/email already in use
    // also checks for password complexity
    // Authors: Dan, Adam
    public function registerCust($email, $fname, $lname, $address, $phone, $pword, $pword2)
    {
        $errors = array(); // list of errors
     
        if (strcmp($pword, $pword2)) // check passwords match
            $errors[] = 'password';

        // different regexes for clarity
        $containsLowerLetter  = preg_match('/[a-z]/',    $pword);
        $containsUpperLetter  = preg_match('/[A-Z]/',    $pword);
        $containsDigit   = preg_match('/\d/',          $pword);
        $containsSpecial = preg_match('/[^a-zA-Z\d]/', $pword);

        $goodPassword = $containsLowerLetter && $containsUpperLetter && $containsDigit && $containsSpecial && strlen($pword) >=6;

        // Only enforce password strength if this file doesnt exist
        if (!file_exists('IGNORE_PASSWORD_STRENGTH') && ! $goodPassword)
            $errors[] = 'pstrength';
  
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
            $this->redirect("register.php?error=".htmlspecialchars(urlencode($errors)));
            echo $errors;
            return false;
            //header("Location: /register.php?error=".htmlspecialchars(urlencode($errors))); 
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

    // Main management page for managers
    public function managementPageOwner()
    {


    }

    // displays the form for adding employees
    public function addEmpFormOwner($added)
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
        require_once('models/AppType.class.php');
        
        $site = new SiteContainer();
        $page = new AddEmpOwner();
        $error_page = new FormError();

        $site->printHeader();
        $site->printNav("owner");
        $error_page->printHtml($error);
        if($added)    {
            $page->printSuccessHtml();
        }
        else    {
            $page->printHtml(AppType::get_all_types($this->db));
        }
        $site->printFooter();   

    }

    // processes and adds entered employee into the database
    public function addEmpOwner($fname,$lname, $skills)
    {
        if (!preg_match("/^[a-zA-Z'-]+$/", $fname))    {
            $error = 'fname';
            header("Location: empOwnerAdd.php?error=$error"); //Check first name
            return false;
        } 
        
        if (!preg_match("/^[a-zA-Z'-]+$/", $lname))    {
            $error = 'lname';
            header("Location: empOwnerAdd.php?error=$error"); //Check last name
            return false;
        } 
        else    {
            $empID = NULL;
            $q = $this->db->prepare("INSERT INTO Employees (empID, fName, lName)
            VALUES (?,?,?);");
            $q->bind_param('sss', $empID, $fname, $lname);
            $q->execute();
            //Insert new employee
            
            //get employees id
            $q = $this->db->prepare("select empID from Employees where fName = ? and empID >= all(select empID from Employees)");
            $q->bind_param('s', $fname);
            $q->execute();
            $empID = $q->get_result();
            $row = mysqli_fetch_assoc($empID);
            
            // add skills
            foreach ($skills as $key => $value)
            {
                if ($value == 1)
                {   
                    $q = $this->db->prepare("insert into haveSkill (empID, typeId) values (?, ?)");
                    $q->bind_param('ss', $row['empID'], $key);
                    $q->execute();
                }
            }
            
             return true;
        }
        
    }

   // displays the form for adding new Appointment Types
    // possibly lists all current app types too?
    public function addActivityFormOwner($added)
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

        require_once('views/AddActivityOwner.class.php');
        require_once('views/FormError.class.php');
        $site = new SiteContainer();
        $page = new AddActivityOwner();
        $error_page = new FormError();

        $site->printHeader();
        $site->printNav("owner");
        $error_page->printHtml($error);
        if($added)    {
            $page->printSuccessHtml();
        }
        else    {
            $page->printHtml();
        }
        $site->printFooter();   

    }


    // processes and adds new activity type into the database
    public function addActivityOwner($appType,$appDesc,$appDuration)
    {
        if (!preg_match("/^[a-zA-Z0-9'-. ]+$/", $appType))    { // need to check regex
            $error = 'appType'; 
            header("Location: addActivityOwner.php?error=$error"); // need to add appropriate error
            return false;
        } 
        
        if (!preg_match("/^[a-zA-Z0-9'-. ]+$/", $appDesc))    { // need to check regex
            $error = 'appDesc';
            header("Location: addActivityOwner.php?error=$error"); 
            return false;
        } 
        if (!preg_match("/^[1-4]+$/", $appDuration))    { // need to check regex
            $error = 'appDuration';
            header("Location: addActivityOwner.php?error=$error"); 
            return false;
        } 
        else    {
            $q = $this->db->prepare("INSERT INTO AppType (appType, appDesc, appDuration)
            VALUES ('$appType','$appDesc', '$appDuration')");
        
            if (!$q->execute())
                return false;
                
            return true;
        }
        
    }
    
    // Handles requests for the help page
    // Authors: Dan
    public function helpPage()
    {
        $site = new SiteContainer();
        $page;

        if ($_SESSION['type'] == 'owner'){
            require_once('views/OwnerHelpPage.class.php');
            $page = new OwnerHelpPage();
        }
        else {
            require_once('views/CustHelpPage.class.php');
            $page = new CustHelpPage();
        }

        $site->printHeader();
        $site->printNav($_SESSION['type']);
        $page->printHtml();
        $site->printFooter();   

    }

    // Very basic if logged in function
    // Authors: Adam
    public function custLoggedIn()
    {
        // check session for an email and customer type
        if ( empty($_SESSION['email']) || $_SESSION['type'] != 'customer')
            return false;
        else
            return true;
        // // Now check whether these are in our database, and add to user
        // // attribute for later use
        // try{
        //     $this->user = new Customer($_SESSION['email'], $this->db);
        // } catch (Exception $e)
        // {
        //     return false;
        // }
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
        if (isset($_SESSION['type']))
            $site->printNav($_SESSION['type']);
        echo "You are not allowed to access this resource. Return <a href=\"index.php\">Home</a>";
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
    public function err_page()
    {
        $site = new SiteContainer();

        $site->printHeader();
        $site->printNav();
        echo "An Error has occured and your request could not be completed. Return <a href=\"index.php\">Home</a>";
        $site->printFooter();
    }
    // Handles PHP redirects
    public function redirect($page = '')
    {
        // If not running under apache (ie test case), dont attempt to redirect
        if ( isset($_SERVER['HTTP_HOST']) )
        {
            $site_url = empty($this->config['site_url']) ? '//'.$_SERVER['HTTP_HOST'] : $this->config['site_url'];

            //header('Location: ' . $site_url . $page);
            header('Location: ' . $page);
        }
    }
    
    public function add_working_times($times) // process an employee working times to add one by one to the database
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

        header('Location: WorkerAvailability.php');
        
        return true;
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
    
    public function add_working_time($empID, $start, $end) // associate employee to all appointments between $start and $end
    {
        if ($end <= $start)
            return false;
        
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

    /**
     * Handles Ajax request for employee calendar HTML.
     * This function is used by both owner and employee to generate respective calendars
     * If a customer is logged in, we want to show them available appointments always
     * If an owner, we only want to show them available appointments in the case of them
     * making an appointment on behalf of an owner
     * Authors: Adam, Anthony
     */
    public function getCustCal($empNo, $weeks, $caltype = '', $displaySlotType = '')
    {
        require_once('models/Calendar.class.php');
        require_once('models/AppType.class.php');
        
        // Determine which calendar we want
        if ($_SESSION['type'] == 'owner') // default booked appointments only
            $displaySlotType = empty($displaySlotType) ? 'booked' : $displaySlotType;
        else // A customer
            $displaySlotType = 'free'; // free times only
        
        $cal = new Calendar($this->db);
        
        try{
            if (!preg_match("/^[a-zA-Z0-9_ ]+$/", $caltype)) // need to check regex
                  throw new Exception("The provided appointment type was not valid.");
                  
            // Attempt to generate the calendar
            if ($displaySlotType == 'booked')
            {
                // Combined calendar view
                if ($caltype == 'combined'){
                    if ( $json_cal = $cal->ajaxGetOwnerCombinedCal($empNo, $weeks) )  // Successful, send calendar
                        echo json_encode(array("success"=>true,"content"=>$json_cal));
                    else
                        throw new Exception("Failed to render Calendar");
                }
                else { // Otherwise show all bookings in each seperate employee calendar 
                    if ( $json_cal = $cal->ajaxGetOwnerCal($empNo, $weeks) )  // Successful, send calendar
                        echo json_encode(array("success"=>true,"content"=>$json_cal));
                    else
                        throw new Exception("Failed to render Calendar");
                }
            }
            else
            {
                // If selecting calendar by appointment type (numeric value, retrieves appType object & correct calendar)
                if (is_numeric($caltype) && $appType = new AppType($caltype, $this->db)){
                    if ($json_cal = $cal->ajaxGetCustCalByType($empNo, $weeks, $appType))
                        echo json_encode(array("success"=>true,"content"=>$json_cal));
                }
                elseif ( $json_cal = $cal->ajaxGetCustCal($empNo, $weeks) )  // Successful, send calendar
                    echo json_encode(array("success"=>true,"content"=>$json_cal));
                else
                    throw new Exception("Failed to render Calendar");
            }
        }catch(Exception $e){
          echo json_encode(array("success"=>false,"content"=>array(), "error"=>$e->getMessage()));
          return false;
        }

    }
    
    /**
     * Wrapper function that determines what to do if an owner requests a
     * timestamp and ID, should we display a booking or are we requesting to
     * create one for a client?
     * Authors: Adam Young
     */
    public function bookingOwner($empId, $dt, $typeId='')
    {
        if ( !$this->ownerLoggedIn() )
        {
            $this->restricted();
            return;
        }
        require_once('models/Booking.class.php');
        
        // Grab the booking we want
        $bk = new Booking($empId, $dt, $this->db);
        // If there is no booking, we must be booking for a customer
        if ( empty($bk->get_email()) )
            $this->bookingConfirm($empId, $dt, $typeId);
        else
            $this->bookingView($empId, $dt);
        
    }
    
    /**
     * Confirms that a user would like to book an appointment with employee and time
     * Authors: Adam, Anthony
     */
    public function bookingConfirm($empId, $dt, $typeId='', $errors='')
    {
        require_once('models/AppType.class.php');
        require_once('models/Customer.class.php');
        require_once('models/Booking.class.php');
        require_once('models/CanWork.class.php');
        require_once('views/BookingView.class.php');

        $site = new SiteContainer();
        $bkv = new BookingView();

        // This wont be different regardless of owner/customer
        if (! ($empdata = $this->fetchEmployeeFromDb($empId)) )
            throw new Exception("Employee $empId could not be found for booking at time: ".$dt->format('Y-m-d H:i:s'));

        $apptypes = AppType::get_types_for_employee($empId, $this->db);
        if ( count($apptypes) == 0 )
            throw new Exception("Appointment types with employee with ID $empId could not be found for booking at time: ".$dt->format('Y-m-d H:i:s'));
    
        try{ // Print out booking to be booked/view booking
            $site->printHeader();
            
            $cw = new CanWork($empId, $dt, $this->db); // not a booking yet!!
            
            $site->printNav($_SESSION['type']);
            if ($_SESSION['type'] == 'owner')
                $bkv->printConfirmOwner($cw, $empdata, $apptypes,$typeId, $errors);
            else
                $bkv->printConfirm($cw, $empdata, $apptypes,$typeId);
            
            $site->printFooter();   
                
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
    
    // View bookings handler
    // Author: Adam
    public function bookingView($empId, $dt)
    {
        require_once('models/Calendar.class.php');
        require_once('models/Customer.class.php');
        require_once('models/Booking.class.php');
        require_once('models/CanWork.class.php');
        require_once('views/BookingView.class.php');

        $site = new SiteContainer();
        $cal = new Calendar($this->db);
        $bkv = new BookingView();
        
        // Grab the booking we want
        $bk = new Booking($empId, $dt, $this->db);
        
        // This wont be different regardless of owner/customer
        if (! ($empdata = $this->fetchEmployeeFromDb($empId)) )
            throw new Exception("Employee $empId could not be found for booking at time: ".$dt->format('Y-m-d H:i:s'));
    
        try {
            // Customer already throws an exception if it cant find a user, we need to catch it
            // and rethrow a new error
            $cust = new Customer($bk->get_email(), $this->db, false); // no bookings please
        }
        catch(Exception $e) { // No Customer was found
            $site->printHeader();
            $site->printNav("owner");
            if ( empty($bk->get_email()) )
                echo "Unable to find a booking at time ".$dt->format('Y-m-d H:i:s')." with Employee: $empId";
            else // Some other error
                echo "Unable to find customer with email: ".$bk->get_email()." booking at time: ".$dt->format('Y-m-d H:i:s')." with Employee: $empId";
            
            $site->printFooter();
            // return, nothing we can do
            return;
        }
        
        $site->printHeader();
        
        $site->printNav("owner");
        $bkv->printOwnerBookingInfo($bk, $empdata, $cust); 
        
        $site->printFooter();
    }
    
    /**
     * books an appointment with employee and time
     * If a customer email is supplied, we are booking the appointment as an
     * owner and should take the customer email as an argument
     * Authors: Adam
     */
    public function bookingCreate($empID, $dt, $custEmail = '', $typeId='')
    {
        require_once('models/Customer.class.php');
        require_once('models/Calendar.class.php');
        require_once('models/CanWork.class.php');
        require_once('models/AppType.class.php');
        require_once('views/BookingView.class.php');

        $site = new SiteContainer();
        $bkv = new BookingView();
        $cal = new Calendar($this->db);
        $cust = null; // Needed in order to not print a customer dropdown if logged in as customer
        
        // If no email supplied, we are booking for the current customer
        if ($this->custLoggedIn() && empty($custEmail))
        {
            $custEmail = $_SESSION['email'];
        } else if ($this->ownerLoggedIn() && empty($custEmail))
        {
            $errors= "Please provide a valid email address to create this appointment";
            $this->bookingConfirm($empID, $dt, $typeId, $errors);
            return;
        } else if ($this->ownerLoggedIn()) // email not empty
        {
            try{
                $cust = new Customer($custEmail, $this->db);
            } catch(Exception $e){
                // Was unable to find customer with this email
                $errors = "Email must be of an existing valid customer";
                // Re=present the booking confirmation
                $this->bookingConfirm($empID, $dt, $typeId, $errors);
                return;
            }
        }
        
        // Grab the canwork from the databse
        $cw = new CanWork($empID, $dt, $this->db); // Still not a booking!!
        $appType = new AppType($typeId, $this->db);
        
        // Grab the employee from the database
        if (! ($empdata = $this->fetchEmployeeFromDb($empID)) )
            throw new Exception("Employee $empID could not be found for booking at time: ".$dt->format('Y-m-d H:i:s'));

        // check that it hasnt already been taken
        try {
            $b_meta = $cal->bookingCheckAndReturnMeta($empID, $dt, $appType);
        } catch (Exception $e) {
            $site->printHeader();
            $site->printNav($_SESSION['type']);
            $bkv->printError($e->getMessage());
            $site->printFooter();
            return; // Its not possible to create this booking
        }
        
        // Here we want to insert the booking
        $sql = "INSERT INTO Bookings (empID, email, datetime, startTime, endTime, appType) VALUES (?,?,?,?,?,?);";
        $stmt = $this->db->prepare($sql);

        // Insert our given username into the statement safely
        $stmt->bind_param('sssssi',
            $empID,
            $custEmail,
            $b_meta['dateTime'],
            $b_meta['startTime'],
            $b_meta['endTime'],
            $b_meta['typeid']
        );
        
        
        $site->printHeader();
        $site->printNav($_SESSION['type']);
        
        if( $stmt->execute() ) // Our appointment was successfully booked, now it is a booking
        {
            $bk = new Booking($empID, $cw->get_dateTime(), $this->db);
            $this->writeLog("Booking for a " . $bk->get_type() . " successfully created at " . $dt->format('Y-m-d H:i:s')
                        . " with employee " . $empdata->fullName
                        . " for customer " . $cust->get_fullName());
            $bkv->printSuccess($bk, $empdata, $cust);
        } else
        {
            $bkv->printError($stmt->error);
        }

        $site->printFooter();   
        
    }
    
    // handles fetching the view for an owner to view employees' working times
    public function show_worker_availability()
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
        


        
        // add error form
        
        $employees = $this->workers_availability();
        
        require_once('views/FormError.class.php');
        require_once('views/WorkerAvailability.class.php');
        $error_page = new FormError();
        $site = new SiteContainer();
        $page = new WorkerAvailability();

        $site->printHeader();
        $site->printNav("owner");
        $error_page->printHtml($error);
        $page->printHtml($employees);
        $site->printFooter();   

    }

    // handles fetching the view for a customer to view their bookings
    public function view_booking_summary()
    {
        // check logged in
        if ( ! $this->custLoggedIn() )
            $this->redirect("login.php?error=login_required");
            
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
    
    /**
     * Employee doesnt have an object, so fetching it here
     * Authors: Adam
     */
     private function fetchEmployeeFromDb($empId)
     {
         $sql = "SELECT *, CONCAT_WS(' ', fName, lName) as fullName FROM Employees WHERE empID = ?;";
        $stmt = $this->db->prepare($sql);
        // Insert our given username into the statement safely
        $stmt->bind_param('s', $empId);
        // Execute the query
        $stmt->execute();
        // Fetch the result
        $res = $stmt->get_result();

        return $res->fetch_object();
     }
     
     /**
      * Quick and dirty remove employees function
      * Remove an employee from database, cleaning up all foreign keys
      * Authors: adam
      */
    public function deleteEmployee($empId)
    {
        if (empty($empId) || !is_numeric($empId))
            return false;
        
        $sql = "DELETE FROM Bookings
                WHERE empID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $empId);
        $stmt->execute();
        
        $sql = "DELETE FROM CanWork
                WHERE empID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $empId);
        $stmt->execute();
        
        $sql = "DELETE FROM haveSkill
                WHERE empID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $empId);
        $stmt->execute();
        
        $sql = "DELETE FROM Employees
                WHERE empID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $empId);
        $stmt->execute();
        
    }
     
    //BOOK AS OWNER FUNCTIONS
    // AUTHORS: Dan
    
    // prints an input for a customer
    // Possibly can be unsed in a modular way at a later date to search 
    // for customers
    public function searchCustomerBox($errors = '')
    {
        
        $error = array();
        if (!empty($errors))
        {
            $error_string = urldecode($errors);
            $error = explode(',', $error_string);
        }
        
        require_once('views/SearchCustomer.class.php');
        require_once('views/FormError.class.php');
        $page = new SearchCustomer();
        $error_page = new FormError();
        
        $error_page->printHtml($error);
        $page->printHtml();
        
    }
    
    public function bookAsCustomer($custEmail)
    {
        if ( !$this->ownerLoggedIn() )
        {
            $this->restricted();
            return false;
        }
        
        require_once('models/Customer.class.php');

        if (!filter_var($custEmail, FILTER_VALIDATE_EMAIL))    {
            $error = 'email';
            $this->redirect("bookAsCustomer.php?error=$error"); //Check email
            return false;
        } 
        
        // Get the customer from the database
        try {
            $cust = new Customer($custEmail,$this->db);
            return $cust;
        } catch (Exception $e) {
            $this->redirect("bookAsCustomer.php?error=custNotFound");
        }
        
        
    }
    
    public function bookAsCustomerView($cust = '')
    {
        if ( !$this->ownerLoggedIn() )
        {
            $this->restricted();
            return false;
        }
        
        require_once('views/CustMainPageView.class.php');
        require_once('models/BusinessOwner.class.php');
        require_once('models/Customer.class.php');
        
        $site = new SiteContainer();
        $page = new CustMainPageView();

        // Load the Customer model, because this is a customer page
        require_once('models/Customer.class.php');
        // Give the model the email address in the session and the database object

        $query = "SELECT fName, lName, empID
                FROM Employees;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        $empArray = $res->fetch_all(MYSQLI_ASSOC);

        $site->printHeader();
        $site->printNav($_SESSION['type']);  //print the owner navbar
        
        
        require_once('models/AppType.class.php');
        $types = AppType::get_all_types($this->db);
        
        // Get types associated with each employee
        $empTypes = array();
        foreach ($empArray as $e)
        {
            $tarr = array();
            $etypes = AppType::get_types_for_employee($e['empID'], $this->db);
            foreach ($etypes as $t)
                array_push($tarr, $t->get_appType());
            $empTypes[$e['empID']] = $tarr;
        }
        
        // Handle errors for searchbox
        $serr = isset($_GET['errors']) ? $_GET['errors'] : '';
        // Disabled for now AY
        //$this->searchCustomerBox($serr);
        //if (! empty($cust))
        //{
        //    $page->printHtml($cust, $types, $empArray);  //pass in details for the customer
            $page->printHtml(null, $types, $empArray);
            $page->printCalendar($empArray, $empTypes);
        //}
        // $site->printFooter();
        $site->printSpecialFooter(array('calendarByType.js','calendarOwnerBookForCust.js'));
    }
    
    // END BOOK AS OWNER FUNCTIONS
    
    public function add_employee_skills($skills, $employee)
    {
        
        
        foreach ($skills as $key => $value)
        {
            if ($value == 1)
                {   
                    $q = $this->db->prepare("select * from haveSkill where empID = ? and typeId = ?");
                    $q->bind_param('ss', $employee, $key);
                    $q->execute();
                    $result = $q->get_result();

                    if (mysqli_num_rows($result) == 0) 
                    {   
                        $q = $this->db->prepare("insert into haveSkill (empID, typeId) values (?, ?)");
                        $q->bind_param('ss', $employee, $key);
                        $q->execute();
                    }
                }
        }
    }
    
    public function add_skills_page($success)
    {
        if ( !$this->ownerLoggedIn() )
        {
            $this->restricted();
            return false;
        }
            
        require_once('views/AddSkillsView.class.php'); 
        require_once('models/AppType.class.php');
        $types = AppType::get_all_types($this->db);


        $query = "SELECT * from Employees;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $res = $stmt->get_result();
        $empArray = $res->fetch_all(MYSQLI_ASSOC);

        $site = new SiteContainer();
        $page = new AddSkillsView();

        $site->printHeader();
        $site->printNav("owner");
        
        if ($success)
            $page->printSuccess();
            
        $page->printHtml($empArray, $types);
        $site->printFooter(); 
    }
    
    public function kill_time($times)
    {
        $times = $_POST['kill'];
        
        foreach ($times as $key => $value)
        {
            $q = $this->db->prepare("delete from CanWork where empID = ? and dateTime = ?");
            $q->bind_param('ss', $value, $key);
            $q->execute();
            
            $next_time = $key;
            
            while (true)
            {
                $dt = new DateTime($next_time);
                $dt->modify('+'.MINIMUM_INTERVAL.' minutes');
                $next_time = $dt->format("Y-m-d H:i:s");
                $q = $this->db->prepare("select * from CanWork where empID = ? and dateTime = ?");
                $q->bind_param('ss', $value, $next_time);
                $q->execute();
                $result = $q->get_result();

                if (mysqli_num_rows($result) > 0) 
                {
                    $q = $this->db->prepare("delete from CanWork where empID = ? and dateTime = ?");
                    $q->bind_param('ss', $value, $next_time);
                    $q->execute();
                }
                else
                    break;
            }
            
            
            
            
        }
    }
    
    
    
    
    
    
    
    
    
}


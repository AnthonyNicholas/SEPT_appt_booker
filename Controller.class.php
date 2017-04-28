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
            $this->redirect("mainPageCust.php");
        } elseif ($this->ownerLoggedIn())
        {
            $this->redirect("mainPageOwner.php");
        } else
        {
            $this->redirect("login.php");
        }

    }

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

        $site->printHeader();
        $site->printNav($this->user->type);
        $page->printHtml($this->user);
        $page->printCalendar($empArray);
        $site->printFooter();
        // $site->printSpecialFooter("calendarByType.js");

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
    // will need to check for duplicate users/email already in use
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
    // possibly lists all current employees too?
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
            $page->printHtml();
        }
        $site->printFooter();   

    }

    // processes and adds entered employee into the database
    public function addEmpOwner($fname,$lname)
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
     */
    public function getCustCal($empNo, $weeks, $caltype = '')
    {
        require_once('models/Calendar.class.php');
        require_once('models/AppType.class.php');
        
        $cal = new Calendar($this->db);

        if (!preg_match("/^[a-zA-Z0-9 ]+$/", $caltype))    { // need to check regex
              $error = "bad_apptype";
              header("Location: mainPageCust.php?error=$error"); 
        } 
        
        try{
            // Attempt to generate the calendar
            if ($_SESSION['type'] == 'owner')
            {
                // Combined calendar view
                if ($caltype == 'combined'){
                    if ( $json_cal = $cal->ajaxGetOwnerCombinedCal($empNo, $weeks) )  // Successful, send calendar
                        echo json_encode(array("success"=>true,"content"=>$json_cal));
                    else
                        throw new Exception("Failed to render Calendar");
                }
                else{
                    // // Otherwise show all bookings in each seperate employee calendar 
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
                    if ($json_cal = $cal->ajaxGetCustCalByType($empNo, $weeks, $caltype))
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
     * Confirms that a user would like to book an appointment with employee and time
     */
    public function bookingConfirm($empId, $dt, $typeId='')
    {
        require_once('models/Calendar.class.php');
        require_once('models/Customer.class.php');
        require_once('models/Booking.class.php');
        require_once('models/CanWork.class.php');
        require_once('views/BookingView.class.php');

        $site = new SiteContainer();
        $cal = new Calendar($this->db);
        $bkv = new BookingView();

        // This wont be different regardless of owner/customer
        if (! ($empdata = $this->fetchEmployeeFromDb($empId)) )
            throw new Exception("Employee $empId could not be found for booking at time: ".$dt->format('Y-m-d H:i:s'));

        $apptypes = $cal->fetchAvailableTypes($empId, $dt, $this->db);
        if ( count($apptypes) == 0 )
            throw new Exception("Appointment types with employee with ID $empId could not be found for booking at time: ".$dt->format('Y-m-d H:i:s'));
    
        try{ // Print out booking to be booked/view booking
            $site->printHeader();
            
            $cw = new CanWork($empId, $dt, $this->db); // not a booking yet!!
            
            $site->printNav($_SESSION['type']);
            $bkv->printConfirm($cw, $empdata, $apptypes,$typeId);
            
            $site->printFooter();   
                
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
    
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
            throw new Exception("Unable to find customer with email: ".$bk->get_email." booking at time: ".$dt->format('Y-m-d H:i:s')." with Employee: $empID");
        }
        
        $site->printHeader();
        
        $site->printNav("owner");
        $bkv->printOwnerBookingInfo($bk, $empdata, $cust); 
        
        $site->printFooter();
    }
    
    /**
     * books an appointment with employee and time, needs lots of error checking and fallbacks implemented
     * TODO
     */
    public function bookingCreate($empID, $dt, $typeId='')
    {
        require_once('models/Calendar.class.php');
        require_once('models/CanWork.class.php');
        require_once('models/AppType.class.php');
        require_once('views/BookingView.class.php');

        $site = new SiteContainer();
        $bkv = new BookingView();
        $cal = new Calendar($this->db);
        
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
            $site->printNav("cust");
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
            $_SESSION['email'],
            $b_meta['dateTime'],
            $b_meta['startTime'],
            $b_meta['endTime'],
            $b_meta['typeid']
        );
        
        
        $site->printHeader();
        $site->printNav("cust");
        
        if( $stmt->execute() ) // Our appointment was successfully booked, now it is a booking
            $bkv->printSuccess($cw, $empdata);
        else
            $bkv->printError($stmt->error);

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
     
    //BOOK AS OWNER FUNCTIONS
     
    public function searchCustomerView()
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
        
        require_once('views/SearchCustomer.class.php');
        require_once('views/FormError.class.php');
        $site = new SiteContainer();
        $page = new SearchCustomer();
        $error_page = new FormError();
        
        $site->printHeader();
        $site->printNav("owner");
        $error_page->printHtml($error);
        $page->printHtml();
        $site->printFooter();   
        
    }
    
    public function bookAsCustomer($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))    {
            $error = 'email';
            $this->redirect("bookAsCustomer.php?error=$error"); //Check email
            return;
        } 
        
        $cust = $this->db->prepare("SELECT email FROM Customers WHERE email = ?;");
        $cust->bind_param('s', $email);
        $cust->execute();
        $result = $cust->get_result();
        
        if (!$result)   {
            return;
        }
        
        $row = mysqli_fetch_row($result);
        
        if (empty($row[0]))   {
            $error = 'email';
            $this->redirect("bookAsCustomer.php?error=$error"); //Check email
            return;    
        }
        
        if($row[0] == $email)   {
            $_SESSION['cust_email'] = $row[0];
            //Need to set user type
            return true;
        }
  
    }
    
    public function bookAsCustomerView()
    {
        require_once('views/CustMainPageView.class.php');
        $site = new SiteContainer();
        $page = new CustMainPageView();

        // Load the Customer model, because this is a customer page
        require_once('models/Customer.class.php');
        // Give the model the email address in the session and the database object
        try{
            $this->user = new Customer($_SESSION['cust_email'], $this->db);
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

        $site->printHeader();
        $site->printNav($this->user->type);
        $page->printHtml($this->user);
        $page->printCalendar($empArray);
        $site->printFooter();
    }
}


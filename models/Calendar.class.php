<?php
 /**
  * 
 * @author Mariocoski
 * @email mariuszrajczakowski@gmail.com 
 * @github https://github.com/mariocoski/Bootstrap-calendar
 * Copyright (c) 2015 Mariocoski
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * This has been altered into a class to fit our appointment booker class,
 * Controller calls upon this class to render calendar HTML given a employee number
 */
class Calendar
{
  private $db;
  
  public function __construct($db)
  {
    
    $this->db = $db;
  }
  
  /* This will be the function that returns our JSON settings to the clientside js */
  public function ajaxGetCustCal( $empNo , $weeks)
  {
      //HORIZONTAL BIG CALENDAR
      $calendar_id = (int)($empNo); //sanitize numeric value
      $number_of_weeks = (int)($weeks);//sanitize numeric value
      if($number_of_weeks == 0 || $calendar_id == 0){
        return false;
      }
      $booking_url = isset($_POST['booking_url'])? Helper::sanitize($_POST['booking_url']):"";
      $max_display = (isset($_POST['max_display']))? (int)($_POST['max_display']) : 7;
      //set first day
      if(isset($_POST['first_day']) && strtolower($_POST['first_day'])=='sunday'){
        $first_day = 0;
      }else{
        $first_day = 1;
      }
      
      // Query should select available times from given employee
      $query = "SELECT w.empID as calendar_id, t.dateTime as timestamp,
              '' as firstname,
              '' as lastname,
              '' as email,
              '' as phone,
              0 as booked,
              0 as noticed,
              '' as deleted
              FROM CanWork w, TimeSlot t
              WHERE w.dateTime = t.dateTime
              AND w.dateTime NOT IN (
                  SELECT dateTime
                  FROM Bookings
              )
              AND w.empID = ?
              ORDER BY t.dateTime ASC;";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('s', $empNo);
      $stmt->execute();
      $res = $stmt->get_result();
      $results = $res->fetch_all(MYSQLI_ASSOC);
      $helper = new Helper();
    
      $output = $helper->prepareBigOutput($results,$calendar_id,$first_day,$number_of_weeks, $booking_url ,$max_display);
      return $output;

  }

  /* This function handles booking of appointments
   * All of this should be transferred to the controller at some point and a view implemented
   * Brainstorming: its likely none of the current stuff needs to be here and the page
   * should show booking details and a message to confirm the booking
   */
  public function bookingConfirm()
  {
  ?>
    <div class="container">
      <div class="row marg-top">  
       <?php 
       $timestamp = (isset($_GET['timestamp']) && is_numeric($_GET['timestamp']))? (int)$_GET['timestamp'] : "0";
       $calendar_id = (isset($_GET['calendar_id']) && is_numeric($_GET['calendar_id']))? (int)$_GET['calendar_id'] : "0";

      $query = "SELECT empID as calendar_id
                FROM Employees WHERE empID = ?;";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('s', $calendar_id);
      $stmt->execute();
      $res = $stmt->get_result();
      $calendar_details = $res->fetch_all(MYSQLI_ASSOC);
         /*
         $db = new DB();
         $query_calendar = "SELECT * FROM `mariocoski_calendar` WHERE calendar_id=:calendar_id";
         $run_calendar = $db->prepare($query_calendar);
         $run_calendar->execute(array(":calendar_id"=>$calendar_id));
         $calendar_details = $run_calendar->fetchAll(PDO::FETCH_ASSOC); 
         */
          $company_name = (isset($calendar_details[0]['company_name']))?  $calendar_details[0]['company_name']:"Company Name";
          $company_address = (isset($calendar_details[0]['address']))?  $calendar_details[0]['address']:"Street Name";
          $company_postcode = (isset($calendar_details[0]['postcode']))?  $calendar_details[0]['postcode']:"Postcode";
          $company_city = (isset($calendar_details[0]['city']))?  $calendar_details[0]['city']:"City";
          $company_country = (isset($calendar_details[0]['country']))?  $calendar_details[0]['country']:"Country";
          $company_website = (isset($calendar_details[0]['website']))?  $calendar_details[0]['website']:"www.example.com";
        
  }

    /* Returns JSON settings to the clientside js.  The owner calendar shows the booked slots rather than the free ones 
    (seperate calendar for each employee) */

  public function ajaxGetOwnerCal( $empNo , $weeks)
  {
    $calendar_id = (int)($empNo); //sanitize numeric value
    $number_of_weeks = (int)($weeks);//sanitize numeric value
    if($number_of_weeks == 0 || $calendar_id == 0){
    return false;
    }
    $booking_url = isset($_POST['booking_url'])? Helper::sanitize($_POST['booking_url']):"";
    $max_display = (isset($_POST['max_display']))? (int)($_POST['max_display']) : 7;
    //set first day
    if(isset($_POST['first_day']) && strtolower($_POST['first_day'])=='sunday'){
    $first_day = 0;
    }else{
    $first_day = 1;
    }

   $query = "SELECT b.empID as calendar_id, b.dateTime as timestamp,
              '' as firstname,
              '' as lastname,
              '' as email,
              '' as phone,
              1 as booked,
              0 as noticed,
              '' as deleted
              FROM Bookings b
              WHERE b.empID = ?
              ORDER BY b.dateTime ASC;";
      $stmt = $this->db->prepare($query);
      $stmt->bind_param('s', $empNo);
      $stmt->execute();
      $res = $stmt->get_result();
      $results = $res->fetch_all(MYSQLI_ASSOC);
      $helper = new Helper();
    
      $output = $helper->prepareBigOutput($results,$calendar_id,$first_day,$number_of_weeks, $booking_url ,$max_display);
      return $output;
  
    }

    /* Returns JSON settings to the clientside js.  The owner calendar shows the booked slots rather than the free ones 
    (one calendar combining all employees) */


  public function ajaxGetOwnerCombinedCal( $empNo , $weeks)
  {
    // $combined = true;
    
    $calendar_id = (int)($empNo); //sanitize numeric value
    $number_of_weeks = (int)($weeks);//sanitize numeric value
    if($number_of_weeks == 0 || $calendar_id == 0){
    return false;
    }
    $booking_url = isset($_POST['booking_url'])? Helper::sanitize($_POST['booking_url']):"";
    $max_display = (isset($_POST['max_display']))? (int)($_POST['max_display']) : 7;
    //set first day
    if(isset($_POST['first_day']) && strtolower($_POST['first_day'])=='sunday'){
    $first_day = 0;
    }else{
    $first_day = 1;
    }

   $query = "SELECT b.empID as calendar_id, b.dateTime as timestamp,
              '' as firstname,
              '' as lastname,
              '' as email,
              '' as phone,
              1 as booked,
              0 as noticed,
              '' as deleted
              FROM Bookings b
              ORDER BY b.dateTime ASC;";
      $stmt = $this->db->prepare($query);
      $stmt->execute();
      $res = $stmt->get_result();
      $results = $res->fetch_all(MYSQLI_ASSOC);
      $helper = new Helper();
    
      $output = $helper->prepareBigOutput($results,'',$first_day,$number_of_weeks, $booking_url ,$max_display, true);
      return $output;
  
    }

   /* Returns JSON settings to the clientside js.  Retrieves employee availabilities by type of appointment  */

  public function ajaxGetCustCalByType( $empNo , $weeks, $appType)
  {
    
      //HORIZONTAL BIG CALENDAR
      $calendar_id = (int)($empNo); //sanitize numeric value
      $number_of_weeks = (int)($weeks);//sanitize numeric value
      
      // Define appointment type parameters
      $tDur = $appType->get_appDuration();
      $tId = $appType->get_id();
      
      if($number_of_weeks == 0 || $calendar_id == 0){
        return false;
      }
      $booking_url = isset($_POST['booking_url'])? Helper::sanitize($_POST['booking_url']):"";
      $max_display = (isset($_POST['max_display']))? (int)($_POST['max_display']) : 7;
      //set first day
      if(isset($_POST['first_day']) && strtolower($_POST['first_day'])=='sunday'){
        $first_day = 0;
      }else{
        $first_day = 1;
      }
      
      // Query should select available times for given employee
      $query = "SELECT w.empID as calendar_id, t.dateTime as timestamp,
              '' as firstname,
              '' as lastname,
              '' as email,
              '' as phone,
              0 as booked,
              0 as noticed,
              '' as deleted
              FROM CanWork w, TimeSlot t
              WHERE w.dateTime = t.dateTime
              AND w.empID = ?
              -- Depending on the duration of the appointment type, check enough consecutive timeslots are free
              AND NOT EXISTS ( 
                  SELECT b.dateTime
                  FROM Bookings b
                  WHERE b.empID = w.empID
                  AND b.dateTime >= t.dateTime
                  AND b.dateTime < (t.dateTime + INTERVAL (?)*30 MINUTE)
              )
              AND (
                SELECT COUNT(*)
                FROM CanWork w1
                WHERE w1.empID = w.empID
                AND w1.dateTime >= t.dateTime
                AND w1.dateTime < (t.dateTime + INTERVAL (?)*30 MINUTE)
              ) = ?
                
                
-- ------------------------------ For now we take type out of equation, no table HasSkill exists
--              AND w.empID IN (  --Get employees who have right skill for this type of Appointment
--                  SELECT empID
--                  FROM HasSkill
--                  WHERE typeId = ?
--              )
-- ------------------------------
              ORDER BY t.dateTime ASC;";
      $stmt = $this->db->prepare($query);
      // For when hasskill is implemented
      //$stmt->bind_param('isi', $empNo, $tDur, $tId); 
      $stmt->bind_param('iiii', $empNo, $tDur, $tDur, $tDur); 
      $stmt->execute();
      $res = $stmt->get_result();
      $results = $res->fetch_all(MYSQLI_ASSOC);
      $helper = new Helper();
    
      $output = $helper->prepareBigOutput($results,$calendar_id,$first_day,$number_of_weeks, $booking_url ,$max_display);
      return $output;

  }

   /**
    * Fetches the available appointment types for a given employee and datetime
    * Basic Implementation just returns all appointment types atm
    * Authors: Adam Young
    */
    public function fetchAvailableTypes($empId, $dt, $db)
    {
      $types = array();
      $query = "
            SELECT id, appType
            FROM AppType;";
      $res = $this->db->query($query);

      while ($row = $res->fetch_assoc()) {
        $types[$row['id']] = $row['appType'];
      }
      /* free result set */
      $res->free();

      return $types;
    }

   /**
    * Checks if the booking is available to book, based on the type, employee
    * and duration
    * Authors: Adam Young
    */
    public function bookingCheckAndReturnMeta($empId, $dt, $appType)
    {
      $typeid = $appType->get_id();
      $slots = $appType->get_appDuration();
      $applen = $slots * SLOT_LEN; // Minutes
      $dts = $dt->format('Y-m-d H:i');

      if (empty($slots))
        throw new Exception("An appointment type was not supplied");

      $query = "SELECT empID, w.dateTime
              FROM CanWork w
              WHERE w.empID = ?
              -- Depending on the duration of the appointment type, check enough consecutive timeslots are free
              AND w.dateTime IN (
                SELECT w1.dateTime
                  FROM CanWork w1
                  WHERE w1.empID = w.empID
                  AND w1.dateTime >= ?
                  AND w1.dateTime < (? + INTERVAL ? MINUTE)
              )
              AND w.dateTime NOT IN ( 
                  SELECT b.dateTime
                  FROM Bookings b
                  WHERE b.empID = w.empID
                  AND b.dateTime >= w.dateTime
                  AND b.dateTime < (w.dateTime + INTERVAL ? MINUTE)
              ) 
-- ----------------------------- No HasSkill implementation yet
--              AND w.empID IN (  --Get employees who have right skill for this type of Appointment
--                  SELECT empID
--                  FROM HasSkill
--                  WHERE typeId = ?
--              )
-- -----------------------------
              ORDER BY w.dateTime ASC;";

      $stmt = $this->db->prepare($query);
      //$stmt->bind_param('siis', $dts, $applen, $empId, $typeid);
      $stmt->bind_param('issii', $empId, $dts, $dts, $applen, $applen);
      $stmt->execute();
      $res = $stmt->get_result();

      // If our result doesnt give us the exact number of slots we asked for, throw
      if ( $res->num_rows != $slots )
        throw new Exception("Booking of type ".$appType->get_appType()." can not be made at time ".$dt->format('Y-m-d H:i:s')." with employee $empId because only ".$stmt->num_rows." slots out of the required $slots are available");

      // Otherwise we're good
      $booking = array('empId' => $empId, 'typeid' => $typeid);
      $i = 0;
      while($b = $res->fetch_assoc())
      {
        // The first record counts as our booking 
        if ( $i == 0 )
        {
            $booking['dateTime'] = $b['dateTime'];
            $booking['startTime'] = $b['dateTime'];
        }
        // If we are at the last element of the array
        if ( ++$i == $slots )
            $booking['endTime'] = strtotime("+$applen minutes", strtotime($b['dateTime']));
      }
      return $booking;

    }
  
}

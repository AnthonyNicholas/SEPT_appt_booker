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
}

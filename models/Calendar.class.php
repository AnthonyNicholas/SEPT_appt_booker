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
 * I havent figured out how we are going to bring this into the controller but the
 * general idea is process.php will end up as one method for the customer and
 * more will come based on our needs, eg ownerCalenderView or whatever
 */
class Calendar
{
  private $db;
  
  public function __construct($db)
  {
    
    $this->db = $db;
  }
  
  /* This will be the function that returns our JSON settings to the clientside js */
  public function ajaxGetCustCal( $empNo )
  {
    if(isset($_POST)){
  if(isset($_POST['calendar_type']) && !empty($_POST['calendar_type']) && isset($_POST['id']) && is_numeric($_POST['id'])&& isset($_POST['number_of_weeks']) && is_numeric($_POST['number_of_weeks'])){
    if($_POST['calendar_type']=='big'){
     //HORIZONTAL BIG CALENDAR
      $calendar_id = (int)($_POST['id']); //sanitize numeric value
      $number_of_weeks = (int)($_POST['number_of_weeks']);//sanitize numeric value
      if($number_of_weeks == 0 || $calendar_id == 0){
        echo json_encode(array("success"=>false,"content"=>array()));
        echo "test 1";
        exit;
      }
      $booking_url = isset($_POST['booking_url'])? Helper::sanitize($_POST['booking_url']):"";
      $max_display = (isset($_POST['max_display']))? (int)($_POST['max_display']) : 7;
     //set first day
      if(isset($_POST['first_day']) && strtolower($_POST['first_day'])=='sunday'){
        $first_day = 0;
      }else{
        $first_day = 1;
      }
      try{
        // $db = new DB();
        //$db = new PDO('mysql:host=localhost;dbname=appt_booker;encoding=utf8', DB_USER, DB_PASS);
        $query = "SELECT w.empID as calendar_id, a.dateTime as timestamp,
                  '' as firstname,
                  '' as lastname,
                  '' as email,
                  '' as phone,
                  0 as booked,
                  0 as noticed,
                  '' as deleted
                  FROM CanWork w, Appointments a
                  WHERE w.appID = a.appID
                  AND w.appID NOT IN (
                      SELECT appID
                      FROM Bookings
                  )
                  AND w.empID = ?
                  ORDER BY dateTime ASC;";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $empNo);
        $stmt->execute();
        $res = $stmt->get_result();
        $results = $res->fetch_all(MYSQLI_ASSOC);
        $helper = new Helper();
        
        $output = $helper->prepareBigOutput($results,$calendar_id,$first_day,$number_of_weeks, $booking_url ,$max_display);
        echo json_encode(array("success"=>true,"content"=>$output));
        exit;
      }catch(PDOException $e){
        echo json_encode(array("success"=>false,"content"=>array()));
        exit;
      }catch(Exception $e){
        echo json_encode(array("success"=>false,"content"=>array()));
        exit;
      }
    }else{
      echo json_encode(array("success"=>false,"content"=>array()));
      exit;
    }
  }else{
    echo json_encode(array("success"=>false,"content"=>array()));
    echo "test 5".$_POST['calendar_type'];
    // $rest_json = file_get_contents("php://input");
    // echo $rest_json;
    exit;
  }
}else{
  echo json_encode(array("success"=>false,"content"=>array()));
  echo "test 6".$_POST['calendar_type'];
  exit;
}

  }
  
}

// localStorage.setItem(hi = "hi");

?>

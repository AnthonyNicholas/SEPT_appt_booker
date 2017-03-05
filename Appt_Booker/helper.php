
<?php

// Test function.

function writeMsg() {
    echo "Yep, you called the function!";
} 

function getEmployees() {
    $username="antfellow";
    $password="";
    $database="appt_booker";
    
    $conn = mysql_connect(localhost,$username,$password);
    
    $sql = 'SELECT firstname, surname FROM Employees';
    
    mysql_select_db($database);
    $result = mysql_query( $sql, $conn );
    
    return $result;
    
}

function printEmpTable($result) {

    // $row = mysql_fetch_assoc($result);
    // echo $row["firstname"];
    // echo $row["surname"];
    
    echo "<table class='table table-striped'>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Day 1</th>
                    <th>Day 2</th>
                    <th>Day 3</th>
                    <th>Day 4</th>
                    <th>Day 5</th>
                </tr>
            </thead>";

    while ($row = mysql_fetch_assoc($result)) {
        echo "<tbody>
                <tr>
                <td>"; 
                    echo $row["firstname"]." ".$row["surname"];
        echo    "</td>
                <td>Avail on Day 1</td>
                <td>Avail on Day 2</td> 
                <td>Avail on Day 3</td>
                <td>Avail on Day 4</td>
                <td>Avail on Day 5</td>";
    }

    echo "
            </tr>
        </tbody>
    </table>";
}

?>
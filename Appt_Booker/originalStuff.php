<!--// A simple web site in Cloud9 that runs through Apache. Press the 'Run' button on the top to start the web server,-->
<!--// then click the URL that is emitted to the Output tab of the console.-->

<!--//  Note - populates database in PHPmyAdmin at: https://sept-appt-booker-antfellow.c9users.io/phpmyadmin with the following username (and blank password): Username: antfellow-->

<?php

$username="antfellow";
$password="";
$database="appt_booker";

$conn = mysql_connect(localhost,$username,$password);

// $sql = 'INSERT INTO Employees (firstname,surname) VALUES ( "Albert", "Albee")';
// $sql = 'INSERT INTO Employees (firstname,surname) VALUES ( "Bert", "Beatle")';
$sql = 'INSERT INTO Employees (firstname,surname) VALUES ( "Candice", "Chalmers")';


mysql_select_db($database);
$retval = mysql_query( $sql, $conn );

?>

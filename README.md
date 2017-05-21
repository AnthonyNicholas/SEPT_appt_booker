# SEPT_appt_booker
An appointment booking system able to be used by wide range of businesses.

Team Members:
Adam Young s3564209
Anthony Nicholas s3607162
Danny Ho s3603075
Jake Williams s3448342

Tutors name: Lawerence Cavedon
Tute/lab day/time: Tuesday 9.30am

## Installation ##
This PHP application will run on PHP 7.0.16-4 and MySQL Server.

0. Ensure your server has adequate file writing permissions (ie 644). Files must be owned by the same user as the web server.
1. Unzip all files into any directory under public_html.
2. Edit config.php with the database username and password, and your preferred admin account email and password. 
3. Ensure the database name is 'NO_DATABASE'.
4. Load the site url and enter basic business details as prompted.
5. Setup your business hours by clicking on the 'hours' tab.
6. Add your business' services by clicking on the 'Add Appointment type' tab.
7. Add your employees by clicking on the 'New Employee' tab.
8. Add your employees' working times by clicking on the 'roster' tab.
9. Now you can begin using your website inluding creating new bookings.

Default Owner login: owner@email.com 
           Password: pw

## Testing ##
The PHPUnit testing command is phpunit.phar. All test classes are contained
in the tests subfolder and can be run individually or all at once.
Example cammands for the test cases Adam wrote are below.


Test a single function (Will give you more detailed results in the event of failure):

php phpunit.phar --bootstrap Controller.class.php tests/LoginTest.php

Run all tests
php phpunit.phar --bootstrap Controller.class.php --testdox tests

Resources:

https://phpunit.de/getting-started.html

https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html

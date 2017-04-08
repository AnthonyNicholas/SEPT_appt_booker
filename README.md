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

1. Unzip all files into the root directory of public_html. This application will
not work under a subfolder due to the nature of some redirect implementations.

2. Edit config.php with the database username, password and name. Ensure
the database has been created and appt_booker.sql tables and data imported into
the new database.

3. Login to the owner account with the provided credentials.


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

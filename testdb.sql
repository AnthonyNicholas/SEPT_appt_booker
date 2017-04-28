+----+-------------+---------------------+-----------+----------+-------+-------+--------+
| id | calendar_id | timestamp           | firstname | lastname | email | phone | booked |
+----+-------------+---------------------+-----------+----------+-------+-------+--------+
|  1 |      667788 | 2017-03-13 10:00:00 |           |          |       |       |      1 |
|  2 |      667788 | 2017-03-13 11:00:00 |           |          |       |       |      0 |
|  3 |      667788 | 2017-03-13 12:00:00 |           |          |       |       |      1 |
+----+-------------+---------------------+-----------+----------+-------+-------+--------+

# Tried to mirror the other query, doesnt work
SELECT w.empID as calendar_id, a.dateTime as timestamp
FROM CanWork w, Appointments a
INNER JOIN Bookings b
ON a.appID = b.appID
INNER JOIN Employees e
ON e.empID = b.empNo
WHERE w.appID = a.appID ORDER BY dateTime ASC;



SELECT mariocoski_event.*
        FROM mariocoski_event
        INNER JOIN mariocoski_user_calendar
        ON mariocoski_event.calendar_id = mariocoski_user_calendar.calendar_id
        INNER JOIN mariocoski_calendar
        ON mariocoski_calendar.calendar_id = mariocoski_user_calendar.calendar_id
        WHERE mariocoski_calendar.calendar_id=:id ORDER BY timestamp ASC


SELECT w.empID as calendar_id, a.dateTime as timestamp,
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
AND w.empID = 3
ORDER BY dateTime ASC;

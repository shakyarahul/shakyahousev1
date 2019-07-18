<?php
include 'attendancecalendar.php';
require_once '../config.php';
$calendar = new AttendanceCalendar();
echo $calendar->show();

?>

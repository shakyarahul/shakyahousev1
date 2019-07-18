<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once("../../php/database.php");
include_once("../../php/model/User.php");
include_once("../../php/model/Staff.php");

$staff = new Staff();
$result = $staff->getAttendanceReport();
$staff_arr = array();
if($result!=NULL){
	while($row = mysqli_fetch_assoc($result)){
		extract($row);
		$staff = array(
			'datetime' => $datetime,
			'act' => $act,
			'user' => $user,
		);

		array_push($staff_arr, $staff);
	}

	echo json_encode($staff_arr);
}else{
	echo json_encode(array('message'=>'No Attendance Found'));
}
?>
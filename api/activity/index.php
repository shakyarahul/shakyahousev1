<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once("../../php/database.php");
include_once("../../php/model/Activity.php");

$activity = new Activity();
$result = $activity->read();
$activity_arr = array();
if($result!=NULL){
	while($row = mysqli_fetch_assoc($result)){
		extract($row);
		$activity = array(
			'datetime' => $datetime,
			'act' => $act,
			'user' => $username
		);

		array_push($activity_arr, $activity);
	}

	echo json_encode($activity_arr);
}else{
	echo json_encode(array('message'=>'No Activity Found'));
}
?>
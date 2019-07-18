<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once("../../php/database.php");
include_once("../../php/model/Room.php");

$room = new Room();
$result = $room->read();
$room_arr = array();
if($result!=NULL){
	while($row = mysqli_fetch_assoc($result)){
		extract($row);
		$room = array(
			'roomId' => $roomId,
			'name' => $name,
			'type' => $type,
			'currency' => $currency,
			'price' => $price,
			'floor' => $floor,
			'roomNo' => $roomNo
		);

		array_push($room_arr, $room);
	}

	echo json_encode($room_arr);
}else{
	echo json_encode(array('message'=>'No Room Found'));
}
?>
<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once("../../php/database.php");
include_once("../../php/model/Booking.php");

$booking = new Booking("1","1","1","1","1");
if(isset($_GET['size'])){
		$size = mysqli_real_escape_string($conn, $_GET['size']);
}else{
		$size = 0;
}

$result = $booking->read($size);
$booking_arr = array();
if($result!=NULL){
	while($row = mysqli_fetch_assoc($result)){
		extract($row);
		$booking = array(
			'bookingId' => $bookingId,
			'checkIn' => $checkIn,
			'checkOut' => $checkOut,
			'roomNo' => $roomNo,
			'name' => $name,
			'userId' => $userId,
			'roles' => $roles
		);

		array_push($booking_arr, $booking);
	}

	echo json_encode($booking_arr);
}else{
	echo json_encode(array('message'=>'No Bookings Found'));
}
?>
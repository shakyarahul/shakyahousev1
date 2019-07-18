<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once("../../php/database.php");
include_once("../../php/model/Order.php");

$order = new Order();
$result = $order->read();
$order_arr = array();
if($result!=NULL){
	while($row = mysqli_fetch_assoc($result)){
		extract($row);
		$order = array(
			'orderId' => $orderId,
			'datetime' => $datetime,
			'quantity' => $quantity,
			'currency' => $currency,
			'roomNo' => $roomNo,
			'clientName' => $clientName,
			'name' => $name

		);

		array_push($order_arr, $order);
	}

	echo json_encode($order_arr);
}else{
	echo json_encode(array('message'=>'No Orders Found'));
}
?>
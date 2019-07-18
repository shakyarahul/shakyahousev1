<?php

class Order{
	public $orderId; //int
	public $quantity; //int
	//1-->1..*
	public $clients;
	//1..*-->1..*
	public $services;
	public $datetime;

	public function __construct($orderId="",$quantity="",$clients="",$services="",$datetime=""){
		/*CREATE TABLE `db_shakyahouse`.`tbl_order` ( `orderId` INT NOT NULL AUTO_INCREMENT , `quantity` VARCHAR NOT NULL , `clients` INT NOT NULL , `services` INT NOT NULL , PRIMARY KEY (`orderId`), INDEX (`clients`), INDEX (`services`)) ENGINE = InnoDB;*/
		$this->orderId = mysqli_real_escape_string($GLOBALS['conn'],$orderId);
		$this->quantity = mysqli_real_escape_string($GLOBALS['conn'],$quantity);
		$this->clients = mysqli_real_escape_string($GLOBALS['conn'],$clients);
		$this->services = mysqli_real_escape_string($GLOBALS['conn'],$services);
		$this->datetime = mysqli_real_escape_string($GLOBALS['conn'],$datetime);
	}

	public function fetchDetail(){
		$sql = "SELECT * FROM `tbl_order` WHERE `orderId` = '$this->orderId' LIMIT 1";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
    	if(!$result){
    		return false;
    	}
    	$row = mysqli_fetch_assoc($result);
		$this->orderId = $row['orderId'];
		$this->quantity = $row['quantity'];
		$this->clients = $row['clients'];
		$this->services = $row['services'];
		$this->datetime = $row['datetime'];
	return true;		
	}

	public function toString(){
		$jsonArry = json_decode(file_get_contents(URI."/api/service/"),true);
		$name = "";
        foreach($jsonArry as $a => $v){
				if($v['serviceId'] == $services){
						$name = $v['name'];
				}
		}	
		return "Order[orderId = ".$orderId.", name=".$name."]";
	}
	public function read(){
		$sql = "SELECT `orderId`,`tbl_order`.`datetime`,`quantity`,`tbl_service`.`name`,`tbl_service`.`currency`,`tbl_service`.`price`,`tbl_user`.`name` AS `clientName`,`tbl_room`.`roomNo` FROM `tbl_order` INNER JOIN `tbl_user` ON `tbl_user`.`userId` = `tbl_order`.`clients` INNER JOIN `tbl_service` ON `tbl_service`.`serviceId` = `tbl_order`.`services` INNER JOIN `tbl_booking` ON `tbl_booking`.`clients` = `tbl_order`.`clients` INNER JOIN `tbl_room` ON `tbl_room`.`roomId` = `tbl_booking`.`rooms`WHERE `tbl_order`.`datetime` BETWEEN `tbl_booking`.`checkIn` AND `tbl_booking`.`checkOut`";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;

	}
	
}

?>
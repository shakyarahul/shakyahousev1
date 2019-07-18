<?php
/*INSERT INTO `tbl_service` (`serviceId`, `name`, `Currency`, `price`, `detail`) VALUES ('1', 'Laundry', 'USD', '15', 'A laundry service from the hotel. A laundry bag in available in every room where you can keep your clothes for laundry. Housekeeping will collect your laundry or you can directly give laundry to the reception.');*/

class Service{
	public $serviceId; //int
	public $name; //String
	public $currency; //String
	public $price; //String
	public $detail; //String

	public function __construct($serviceId="",$name="",$currency="NPR",$price="",$detail=""){
		/*CREATE TABLE `db_shakyahouse`.`tbl_service` ( `serviceId` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(64) NOT NULL , `price` DOUBLE NOT NULL , `detail` TEXT NOT NULL , PRIMARY KEY (`serviceId`)) ENGINE = InnoDB;*/
		$this->serviceId = mysqli_real_escape_string($GLOBALS['conn'],$serviceId);
		$this->name = mysqli_real_escape_string($GLOBALS['conn'],$name);
		$this->price = mysqli_real_escape_string($GLOBALS['conn'],$price);
		$this->detail = mysqli_real_escape_string($GLOBALS['conn'],$detail);
		$this->currency = mysqli_real_escape_string($GLOBALS['conn'],$currency);
	}
	public function fetchDetail(){
		$sql = "SELECT `name`, `currency`, `price`, `detail` FROM `tbl_service` WHERE `serviceId` = '$this->serviceId' LIMIT 1";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
    	if(!$result){
    		return false;
    	}
    	$row = mysqli_fetch_assoc($result);
    	$this->name =$row['name'];
		$this->price =$row['price'];
		$this->detail =$row['detail'];
		$this->currency = $row['currency'];
		return true;		
	}
	
	public function read($srch=""){
		$sql = "SELECT `serviceId`, `name`, `currency`, `price`, `detail` FROM `tbl_service` WHERE 
				`name` LIKE '%$srch%'";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;

	}

	public function toString(){
		return "Service[serviceId = ".$serviceId.", name=".$name."]";
	}

	
}

?>
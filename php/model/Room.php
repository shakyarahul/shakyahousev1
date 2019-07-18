<?php
/*INSERT INTO `tbl_room` (`roomId`, `name`, `type`, `currency`, `price`, `floor`, `roomNo`) VALUES ('1', 'Shakya Muni', 'Double room with terrrace', 'USD', '45', '3', '302');
*/
class Room{
	public $roomId; //int
	public $name; //String
	public $type; //String
	public $currency; //String
	public $price; //String
	public $floor; //String
	public $roomNo; //String

	public function __construct($roomId="",$name="",$type="",$floor="",$currency="",$price="",$roomNo=""){
		/*CREATE TABLE `db_shakyahouse`.`tbl_room` ( `roomId` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(128) NOT NULL , `type` VARCHAR(64) NOT NULL , `currency` VARCHAR(4) NOT NULL , `price` DOUBLE NOT NULL , `floor` VARCHAR(32) NOT NULL , `roomNo` VARCHAR(32) NOT NULL , PRIMARY KEY (`roomId`)) ENGINE = InnoDB;*/
		$this->roomId = mysqli_real_escape_string($GLOBALS['conn'],$roomId);
		$this->name = mysqli_real_escape_string($GLOBALS['conn'],$name);
		$this->type = mysqli_real_escape_string($GLOBALS['conn'],$type);
		$this->floor = mysqli_real_escape_string($GLOBALS['conn'],$floor);
		$this->currency = mysqli_real_escape_string($GLOBALS['conn'],$currency);
		$this->price = mysqli_real_escape_string($GLOBALS['conn'],$price);
		$this->roomNo = mysqli_real_escape_string($GLOBALS['conn'],$roomNo);
	}


	public function fetchDetail(){
		$sql = "SELECT * FROM `tbl_room` WHERE `roomId` = '$this->roomId' LIMIT 1";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
    	if(!$result){
    		return false;
    	}
    	$row = mysqli_fetch_assoc($result);
    	$this->roomId = $row['roomId'];
		$this->name = $row['name'];
		$this->type = $row['type'];
		$this->floor = $row['floor'];
		$this->currency = $row['currency'];
		$this->price = $row['price'];
		$this->roomNo = $row['roomNo'];
		return true;		
	}

	public function read(){
		$sql = "SELECT * FROM `tbl_room`";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;

	}

	public function toString(){
		return "Room[roomId = ".$roomId.", roomNo=".$roomNo."]";
	}

	
}

?>
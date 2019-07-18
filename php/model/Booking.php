<?php

class Booking{
	public $bookingId; //int
	public $checkIn; //date
	public $checkOut; //date
	//1..*-->1..*
	public $clients;
	//1-->1
	public $rooms;

	public function __construct($bookingId="",$checkIn="",$checkOut="",$clients="",$rooms=""){
		$this->bookingId = mysqli_real_escape_string($GLOBALS['conn'],$bookingId);
		$this->checkIn =mysqli_real_escape_string($GLOBALS['conn'], $checkIn);
		$this->checkOut = mysqli_real_escape_string($GLOBALS['conn'],$checkOut);
		$this->clients = mysqli_real_escape_string($GLOBALS['conn'],$clients);
		$this->rooms = mysqli_real_escape_string($GLOBALS['conn'],$rooms);
	}
	public function fetchDetail(){
		$sql = "SELECT * FROM `tbl_booking` WHERE `bookingId` = '$this->bookingId' LIMIT 1";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
    	if(!$result){
    		return false;
    	}
    	$row = mysqli_fetch_assoc($result);
    	$this->bookingId = $row['bookingId'];
		$this->clients = $row['clients'];
		$this->rooms = $row['rooms'];
		
		return true;		
	}
	public function read($size=null){
		if(empty($size)){
			$sql = "SELECT `bookingId`,`checkIn`,`checkOut`,`tbl_room`.`roomNo`,`tbl_user`.`name`,`tbl_user`.`userId`,`tbl_user`.`emailId`,`tbl_role`.`name` AS 'roles' FROM `tbl_booking` JOIN `tbl_room` ON `tbl_booking`.`rooms` = `tbl_room`.`roomId` JOIN `tbl_user` on `tbl_user`.`userId` = `tbl_booking`.`clients` JOIN `tbl_role` on `tbl_role`.`roleId` = `tbl_user`.`roles` ORDER BY `checkIn` DESC";
		}else{
			$sql = "SELECT `bookingId`,`checkIn`,`checkOut`,`tbl_room`.`roomNo`,`tbl_user`.`name`,`tbl_user`.`userId`,`tbl_user`.`emailId`,`tbl_role`.`name` AS 'roles' FROM `tbl_booking` JOIN `tbl_room` ON `tbl_booking`.`rooms` = `tbl_room`.`roomId` JOIN `tbl_user` on `tbl_user`.`userId` = `tbl_booking`.`clients` JOIN `tbl_role` on `tbl_role`.`roleId` = `tbl_user`.`roles`  ORDER BY `checkIn` DESC LIMIT {$size}";
		}//echo $sql;
    	$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;
		

	}
	public function toString(){
		$jsonArry = json_decode(file_get_contents(URI."/api/room/"),true);
		$name = "";
        foreach($jsonArry as $a => $v){
				if($v['roomId'] == $clients){
						$name = $v['roomNo'];
				}
		}	
		return "Booking[bookingId = ".$bookingId.", room=".$name."]";
	}
}

?>
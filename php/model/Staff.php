<?php

class Staff extends User{

	public function __construct($userId="",$username="",$password="",$name="",$dob="",$emailId="",$address="",$registeredDate="",$passwordNo="",$profilePic="",$mobile="",$roles=""){
		parent::__construct($userId,$username,$password,$name,$dob,$emailId,$address,$registeredDate,$passwordNo,$profilePic,$mobile,$roles);
		/*$this->userId= $userId;
		$this->username= $username;
		$this->password= $password;
		$this->name= $name;
		$this->dob= $dob;
		$this->emailId= $emailId;
		$this->address= $address;
		$this->registeredDate= $registeredDate;
		$this->passwordNo= $passwordNo;
		$this->profilePic= $profilePic;
		$this->mobile= $mobile;
		$this->roles = $roles;*/
	}
	
		

		public function deleteARoom($c){
			$sql = "DELETE FROM `tbl_room` WHERE `roomId` = '$c'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Deleted Room of id=$c",$this->userId);
				$act->insertAct();
			return true;
		}
		public function deleteAOrder($c){
			$sql = "DELETE FROM `tbl_order` WHERE `orderId` = '$c'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Deleted Order of id=$c",$this->userId);
				$act->insertAct();
			return true;
		}
		public function deleteAService($c){
			$sql = "DELETE FROM `tbl_service` WHERE `serviceId` = '$c'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
			$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Deleted Service of id=$c",$this->userId);
			$act->insertAct();
			return true;
		}
		public function deleteAUser($c){
			$sql = "DELETE FROM `tbl_user` WHERE `userId` = '$c'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
			$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Deleted User of id=$c",$this->userId);
				$act->insertAct();
			return true;
		}
		public function deleteABooking($c){
			$sql = "DELETE FROM `tbl_booking` WHERE `bookingId` = '$c'";
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
			$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Deleted Booking of id=$c",$this->userId);
			$act->insertAct();
			return true;
		}

		public function addAClient($c){

			$img = $c->profilePic;
			if(is_array($img)){
				$imgName = $img['name'];
					$imgName = explode(".", $imgName);
				$imgExt = array_pop($imgName);
				$imgName = implode(".", $imgName);
				$imgTempName = $img['tmp_name'];
				$imgError = $img['error'];
				$imgSize = $img['size'];
				$imgType = $img['type'];
				$imageName = $imgName .".".$imgExt;
				if($imgError){
					foreach ($imgError as $key => $value) {
						echo $value;
						die;
					}
				}
			}else {
				$imageName = $c->profilePic;
			}
					
			if(empty($c->userId)){
				$sql = "INSERT INTO `tbl_user`
				(`username`, `password`, `name`, `dob`, `emailId`, `address`, 
				`registeredDate`, `passwordNo`, `profilePic`, `mobile`, `roles`) 
				VALUES ('$c->username', '$c->password', '$c->name', '$c->dob', '$c->emailId', '$c->address', 
				'$c->registeredDate', '$c->passwordNo', '$imageName', '$c->mobile', '$c->roles')";

			}else{
				if($c->userId == $this->userId || $c->roles == "3" ){
				$sql = "UPDATE `tbl_user`
					SET `username` = '$c->username', `password` = '".sha1($c->password)."', `name` = '$c->name', `dob` = '$c->dob', `emailId` ='$c->emailId' , `address` = '$c->address', 
					`registeredDate` = '$c->registeredDate', `passwordNo` = '$c->passwordNo', `profilePic` = '$imageName', `mobile` = '$c->mobile', `roles` = '$c->roles' WHERE `userId` = '$c->userId'";
				
				}else {
					echo "error reporting hack";
					$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Vulnerable User",$this->userId);
						$act->insertAct();
						session_destroy();
						die;

						return false;
				}
			}

			//echo $sql;
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error " .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				if(is_array($img)){
					if(!move_uploaded_file($imgTempName, 'assets/img/profilePic/'.$imgName.".".$imgExt)){
						echo "error in file";
						return false;
					}
				}
				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Inserted Client".$c->toString(),$this->userId);
				$act->insertAct();
			return true;
		}

		public function addARoom($c){
			if(empty($c->roomId)){
				$sql = "INSERT INTO `tbl_room`(`name`, `type`, `currency`, `price`, `floor`, `roomNo`) VALUES ( '$c->name', '$c->type', '$c->currency', '$c->price', '$c->floor', '$c->roomNo')";
			}else{
				$sql = "UPDATE `tbl_room` SET `name`='$c->name',`type`='$c->type',`currency`='$c->currency',`price`='$c->price',`floor`='$c->floor',`roomNo`='$c->roomNo' WHERE `roomId`='$c->roomId'";
			}

			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
			$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Inserted Room".$c->toString(),$this->userId);
			$act->insertAct();
			return true;
		}
		public function addAService($c){
			if(empty($c->serviceId)){
				$sql = "INSERT INTO `tbl_service`(`name`, `currency`, `price`, `detail`) VALUES ('$c->name', '$c->currency', '$c->price', '$c->detail')";					
			}else{
				$sql = "UPDATE `tbl_service` SET `name`='$c->name',`price`='$c->price',`currency`='$c->currency',`detail`='$c->detail' WHERE `serviceId`='$c->serviceId'";
			}
			$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Inserted Service".$c->toString(),$this->userId);
				$act->insertAct();
			return true;
		}
		public function getAttendanceReport(){
		$userid = $this->userId;
		$sql = "SELECT * FROM `tbl_activity` WHERE `act` LIKE '%::1%' ";
		
		$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;
		}

		

	public function toString(){
		return "Staff[userId = ".$this->userId."]";
	}

	
}

?>
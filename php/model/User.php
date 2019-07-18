<?php

class User{

	public $userId; //int
	public $username; //String
	public $password; //String
	public $name; //String
	public $dob; //Date
	public $emailId; //String
	public $address; //String
	public $registeredDate; //String
	public $passwordNo; //String
	public $profilePic; //String
	public $mobile; //String
	/*ALTER TABLE `tbl_user` ADD CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`roles`) REFERENCES `tbl_role` (`roleId`); COMMIT;*/
	public $roles;//Array of RoleObject

	public function __construct($userId="",$username="",$password="",$name="",$dob="",$emailId="",$address="",$registeredDate="",$passwordNo="",$profilePic="",$mobile="",$roles=""){
		/*CREATE TABLE `tbl_user` (
  `userId` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `dob` date NOT NULL,
  `emailId` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `registeredDate` date NOT NULL,
  `passwordNo` varchar(64) NOT NULL,
  `profilePic` text NOT NULL,
  `mobile` varchar(64) NOT NULL,
  `roles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;*/
		$this->userId= mysqli_real_escape_string($GLOBALS['conn'],$userId);
		$this->username= mysqli_real_escape_string($GLOBALS['conn'],$username);
		$this->password= mysqli_real_escape_string($GLOBALS['conn'],$password);
		$this->name= mysqli_real_escape_string($GLOBALS['conn'],$name);
		$this->dob= mysqli_real_escape_string($GLOBALS['conn'],$dob);
		$this->emailId= mysqli_real_escape_string($GLOBALS['conn'],$emailId);
		$this->address= mysqli_real_escape_string($GLOBALS['conn'],$address);
		$this->registeredDate= mysqli_real_escape_string($GLOBALS['conn'],$registeredDate);
		$this->passwordNo= mysqli_real_escape_string($GLOBALS['conn'],$passwordNo);
		$this->profilePic= $profilePic;
		$this->mobile= mysqli_real_escape_string($GLOBALS['conn'],$mobile);
		$this->roles = mysqli_real_escape_string($GLOBALS['conn'],$roles);
	}

	public function fetchDetail(){
		$sql = "SELECT * FROM `tbl_user` WHERE `userId` = '$this->userId' LIMIT 1";
    	$result = mysqli_query($GLOBALS['conn'],$sql);
    	if(!$result){
    		return false;
    	}
    	$row = mysqli_fetch_assoc($result);
    	$this->userId= $row['userId'];
		$this->username= $row['username'];
		$this->password= $row['password'];
		$this->name= $row['name'];
		$this->dob= $row['dob'];
		$this->emailId= $row['emailId'];
		$this->address= $row['address'];
		$this->registeredDate= $row['registeredDate'];
		$this->passwordNo= $row['passwordNo'];
		$this->profilePic= $row['profilePic'];
		$this->mobile= $row['mobile'];
		$this->roles = $row['roles'];
    	return true;
				
	}


	public function toString(){
		return "User[userId = ".$userId.", username=".$username.", password=".$password.", name=".$name.", dob=".$dob.", emailId=".$emailId.", address=".$address.", registeredDate=".$registeredDate.", passwordNo=".$passwordNo.", profilePic=".$profilePic.", mobile=".$mobile.", roles=".$roles.toString()."]";
	}
	public function verifyRole(){
		
	}
	public function verifyLogOut(){
		$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Logged Out : ".$this->getUserIpAddr(),$this->userId);
		$act->insertAct();
		session_destroy();
		
	}
	public function verifyLogin(){
		$sql = "SELECT `username`,`password`,`tbl_user`.`name`,`dob`,`emailId`,`address`,`registeredDate`,`passwordNo`,`profilePic`,`mobile`,`userId`,`tbl_role`.`name` AS `roles` FROM `tbl_user` LEFT JOIN `tbl_role` ON `tbl_user`.`roles` = `tbl_role`.`roleId` WHERE `username`='$this->username' AND `password` = '$this->password' LIMIT 1";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		
		if(!$result){
			echo "<br />result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
			return false;
		}
		$row = mysqli_fetch_assoc($result);
		if(empty($row)){
			echo "user/password error";
			return false;
		}
		$_SESSION["password"] = $this->password;

		$this->userId= mysqli_real_escape_string($GLOBALS['conn'],$row['userId']);
		$this->username= mysqli_real_escape_string($GLOBALS['conn'],$row['username']);
		$this->password= mysqli_real_escape_string($GLOBALS['conn'],$row['password']);
		$this->name= mysqli_real_escape_string($GLOBALS['conn'],$row['name']);
		$this->dob= mysqli_real_escape_string($GLOBALS['conn'],$row['dob']);
		$this->emailId= mysqli_real_escape_string($GLOBALS['conn'],$row['emailId']);
		$this->address= mysqli_real_escape_string($GLOBALS['conn'],$row['address']);
		$this->registeredDate= mysqli_real_escape_string($GLOBALS['conn'],$row['registeredDate']);
		$this->passwordNo= mysqli_real_escape_string($GLOBALS['conn'],$row['passwordNo']);
		$this->profilePic= mysqli_real_escape_string($GLOBALS['conn'],$row['profilePic']);
		$this->mobile= mysqli_real_escape_string($GLOBALS['conn'],$row['mobile']);
		$this->roles = mysqli_real_escape_string($GLOBALS['conn'],$row['roles']);

if( !(isset($_SESSION["username"]) && isset($_SESSION["password"]) ) ){
//		echo date("Y-m-d",strtotime("now"))." ".date("H:i:s",strtotime("now"));
	$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Logged In : ".$this->getUserIpAddr(),$this->userId);
		$act->insertAct();
	}

		$_SESSION["username"] = $row['username'];
		$_SESSION["role"] = $row['name'];

		return true;
	}

	public function logout(){

		session_destroy();
	}
	public function getUserIpAddr(){
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			//ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			//ip pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	public function read($srch = ""){
		$srch = mysqli_real_escape_string($GLOBALS['conn'],$srch);
		$sql = "SELECT `tbl_user`.`registeredDate`,`tbl_user`.`name`,`tbl_user`.`emailId`,`dob`,`address`,`userId`,`tbl_role`.`name` AS `roles` FROM `tbl_user` LEFT JOIN `tbl_role` ON `tbl_user`.`roles` = `tbl_role`.`roleId` WHERE 
				`tbl_user`.`passwordNo` LIKE '%$srch%' OR 
				`tbl_user`.`name` LIKE '%$srch%' OR 
				`tbl_user`.`emailId` LIKE '%$srch%' OR 
				`address` LIKE '%$srch%' ORDER BY `registeredDate` DESC";
		
		$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;

	}

	public function bookARoomForClient($bid,$c,$r,$cIn,$cOut){
			
		if(isset($_SESSION['username']) && isset($_SESSION['password'])){
			$user = new User("",$_SESSION['username'],$_SESSION['password']);
			if($user->verifyLogin()){
				$checkIn = mysqli_real_escape_string($GLOBALS['conn'],$cIn);
				$checkOut = mysqli_real_escape_string($GLOBALS['conn'],$cOut);
				$room = mysqli_real_escape_string($GLOBALS['conn'],$r);
				$client = mysqli_real_escape_string($GLOBALS['conn'],$c);
				$bookingId = mysqli_real_escape_string($GLOBALS['conn'],$bid);
				if(empty($bid)){
					$sql = "INSERT INTO `tbl_booking`(`checkIn`, `checkOut`, `clients`, `rooms`) 
							VALUES ('$checkIn', '$checkOut', '$c', '$room')";
				}else {
					$sql = "UPDATE `tbl_booking` SET `checkIn` = '$checkIn', `checkOut` = '$checkOut', `rooms`='$room',`clients`='$client' WHERE `bookingId` = '$bookingId'";

				}
				
				//echo "<br /><br />".$sql."<br /><br />"; die;
				$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Booked Room $checkIn to $checkOut",$this->userId);
				$act->insertAct();
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function orderServiceForClient($oid,$c,$s,$q,$d){
		if(isset($_SESSION['username']) && isset($_SESSION['password'])){
			$user = new User("",$_SESSION['username'],$_SESSION['password']);
			if($user->verifyLogin()){
				$service = mysqli_real_escape_string($GLOBALS['conn'],$s);
				$client = mysqli_real_escape_string($GLOBALS['conn'],$c);
				$quantity = mysqli_real_escape_string($GLOBALS['conn'],$q);
				$datetime = mysqli_real_escape_string($GLOBALS['conn'],$d);
				$orderId = mysqli_real_escape_string($GLOBALS['conn'],$oid);
				if(empty($oid)){
					$sql = "INSERT INTO `tbl_order`(`quantity`, `clients`, `services`,`datetime`) VALUES ('$quantity','$client','$service','$datetime')";
				}else {
					$sql = "UPDATE `tbl_order` SET `quantity` = $quantity, `clients` = '$client', `services`='$service',`datetime`='$datetime' WHERE `orderId` = '$orderId'";
				}
				//echo $sql;
				$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br />result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Ordered Service quantity = $quantity" ,$this->userId);
				$act->insertAct();
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function promote($user){
		
		$this->userId= mysqli_real_escape_string($GLOBALS['conn'],$user->userId);
		$this->username= mysqli_real_escape_string($GLOBALS['conn'],$user->username);
		$this->password= mysqli_real_escape_string($GLOBALS['conn'],$user->password);
		$this->name= mysqli_real_escape_string($GLOBALS['conn'],$user->name);
		$this->dob= mysqli_real_escape_string($GLOBALS['conn'],$user->dob);
		$this->emailId= mysqli_real_escape_string($GLOBALS['conn'],$user->emailId);
		$this->address= mysqli_real_escape_string($GLOBALS['conn'],$user->address);
		$this->registeredDate= mysqli_real_escape_string($GLOBALS['conn'],$user->registeredDate);
		$this->passwordNo= mysqli_real_escape_string($GLOBALS['conn'],$user->passwordNo);
		$this->profilePic= mysqli_real_escape_string($GLOBALS['conn'],$user->profilePic);
		$this->mobile= mysqli_real_escape_string($GLOBALS['conn'],$user->mobile);
		$this->roles = mysqli_real_escape_string($GLOBALS['conn'],$user->roles);
	}
}

?>
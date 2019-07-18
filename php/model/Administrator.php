<?php
class Administrator extends Staff{

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

	public function setRoles($u,$r){
		if(isset($_SESSION['username']) && isset($_SESSION['password'])){
			$user = new User("",$_SESSION['username'],$_SESSION['password']);
			if($user->verifyLogin()){
				$userId = mysqli_real_escape_string($GLOBALS['conn'],$u);
				$role = mysqli_real_escape_string($GLOBALS['conn'],$r);
				$sql = "UPDATE `tbl_user` SET `roles`= '$role' WHERE `userId` = '$userId'";
				//echo $sql;die;
				$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br />result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}

				$act = new Activity(date("Y-m-d H:i:00",strtotime("now")),"Roles Updated" ,$this->userId);
				$act->insertAct();
				return true;

			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function toString(){
		return "Administrator[userId = ".$this->userId."]";
	}

	
}
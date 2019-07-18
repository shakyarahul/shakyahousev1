<?php

class Client extends User{

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

	
	public function toString(){
		return "Client[userId = ".$this->userId.", username=".$this->username."]";
	}	
}

?>
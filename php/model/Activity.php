<?php
class Activity{

	public $activityId;
	public $datetime;
	public $act;
	public $user;

	public function __construct($datetime="",$act="",$user=""){
		
		$this->datetime = mysqli_real_escape_string($GLOBALS['conn'],$datetime);
		$this->act = mysqli_real_escape_string($GLOBALS['conn'],$act);
		$this->user = mysqli_real_escape_string($GLOBALS['conn'],$user);
	}

	public function insertAct(){

		$sql = "INSERT INTO `tbl_activity`(`datetime`, `act`, `user`) VALUES ('$this->datetime','$this->act','$this->user')";
				//echo "<br /><br />".$sql."<br /><br />";
		$result = mysqli_query($GLOBALS['conn'],$sql);
				if(!$result){
					echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
					return false;
				}
				
	}

	public function read(){
		$sql = "SELECT `datetime`,`act`,`username` FROM `tbl_activity` JOIN `tbl_user` ON `user` = `tbl_user`.`userId` ORDER BY `datetime` DESC";
		$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;

	}
}
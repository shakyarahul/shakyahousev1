<?php
class Role{
	public $roleId; //int
	public $name; //String
	
	public function __construct($roleId="",$name=""){
		//CREATE TABLE `db_shakyahouse`.`tbl_role` ( `roleId` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(25) NOT NULL , PRIMARY KEY (`roleId`), UNIQUE (`name`)) ENGINE = InnoDB;
		$this->roleId = mysqli_real_escape_string($GLOBALS['conn'],$roleId);
		$this->name = mysqli_real_escape_string($GLOBALS['conn'],$name);
	}
	public function read(){
		$sql = "SELECT * FROM `tbl_role`";
		//echo $sql;
    	$result = mysqli_query($GLOBALS['conn'],$sql);
		return $result;

	}
	public function toString(){
		return "Role[roleId = ".$roleId.", name=".$name."]";
	}
}

?>
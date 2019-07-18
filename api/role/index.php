<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');


include_once("../../php/database.php");
include_once("../../php/model/Role.php");

$role = new Role();
$result = $role->read();
$role_arr = array();
if($result!=NULL){
	while($row = mysqli_fetch_assoc($result)){
		extract($row);
		$role = array(
			'roleId' => $roleId,
			'name' => $name,
		);

		array_push($role_arr, $role);
	}

	echo json_encode($role_arr);
}else{
	echo json_encode(array('message'=>'No Roles Found'));
}
?>
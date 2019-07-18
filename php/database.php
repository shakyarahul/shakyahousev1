<?php

//connection variable
define("HOST", "148.66.138.104");
define("USER", "shakyahouse");
define("PASS", "0@thK33p3R");
define("DATA", "db_shakyahouse");

$conn = mysqli_connect(HOST,USER,PASS,DATA);
if(!$conn){
	echo "error on database".USER;
	die;
}else{
	//echo "Success in connection database";
}


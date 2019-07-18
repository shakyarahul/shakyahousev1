<?php
			include 'php/config.php';
			?><a href="index.php">Back</a><?php
    		$user = new User("",$_SESSION["username"],$_SESSION["password"]);
			$user->verifyLogin();
			$user->verifyLogOut();
            header("Location: index.php");
?>
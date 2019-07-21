<?php
include 'php/config.php';
$user = null;
if(isset($_SESSION["username"]) && isset($_SESSION["password"]) ){
        $user = new User("",$_SESSION["username"],($_SESSION["password"]));
        if($user->verifyLogin()){
			$u = $user;
			if($user->roles == "ADMIN"){
					$user = new Administrator(); 
			}else if($user->roles == "STAFF"){
					$user = new Staff();
			}else if($user->roles == "GUEST"){
					$user = new Client();
			}else{
			
			}
			$user->promote($u);
			echo "Greetings, ". $user->roles;
        }else{
            session_destroy();
            header('Location: index.php');
        }
}else{
		session_destroy();
		header('Location: index.php');
}

if(isset($_POST['generate'])){
	$currency = $_POST['currency'];
	$currency = explode("-", $currency);

	$userId = $_POST['userId'];
	$user = new Client($userId);
	$user->fetchDetail();
}else {
	die;
} 
include './header.php';
?>


<body>
<a href="index.php">Back</a>
	<script type="text/javascript">
		function editingSpan(a){
			document.getElementById(a).contentEditable = true;
		}
	</script>
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="4"><img src="assets/img/logo.png">
                            <ul class="list-unstyled">
                                <li>Chakupat, Lalitpur, Patan<br></li>
                                <li>Ph :: 015260266 / 015260144 / 9841280625<br></li>
                                <li>Email :: theshakyahouse@gmail.com<br></li>
                                <li>Website : www.shakyahouse.com<br></li>
                            </ul>
                        </th>
                        <th colspan="3">
                            <ul class="list-unstyled" style="margin:0px;">
                                <li>Invoice #</li>
                                <li>PAN # 603323265</li>
                                <li>Date <span id="editDate" ondblclick="editingSpan('editDate')" style="text-decoration: underline;"><?= date("Y-m-d") ?></span></li>
                                <li>Bill to <span id="editcName" ondblclick="editingSpan('editcName')" style="text-decoration: underline;"><?= $user->name ?></span><br></li>
                                <li>Nationality <span id="editcAddress" ondblclick="editingSpan('editcAddress')" style="text-decoration: underline;"><?= $user->address ?></span><br></li>
                                <li>Mode of payment</li>
                            </ul>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Cash</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Bank Transfer<br></label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Cheque</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Credit Card</label></div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">
                            <p><strong>Name of the Guest/Agent <span id="editName" ondblclick="editingSpan('editName')" style="text-decoration: underline;">
                            	<?= $user->name ?></span>
                            </strong><br></p>
                            <p><strong>Address&nbsp;&nbsp; &nbsp; &nbsp;<span id="editAddress" ondblclick="editingSpan('editAddress')" style="text-decoration: underline;">
                            	
                            	<?= $user->address ?>
                            </span></strong><br></p>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Room</strong></td>
                        <td><strong>Name &amp; Description</strong></td>
                        <td><strong>Check&nbsp;In</strong></td>
                        <td><strong>Check Out</strong><br></td>
                        <td><strong># of Quantity</strong></td>
                        <td><strong>Price / Quantity</strong></td>
                        <td><strong>Line Total</strong><br></td>
                    </tr>
                    <?php
                    	$sql = "SELECT `tbl_room`.`roomNo`,`tbl_room`.`name` AS 'roomName', `tbl_booking`.`checkIn`,`tbl_booking`.`checkOut`,`tbl_room`.`currency`,`tbl_room`.`price` 
						FROM `tbl_booking` 
						JOIN `tbl_room` ON `tbl_room`.`roomId` = `tbl_booking`.`rooms` 
						WHERE `tbl_booking`.`clients` = '$user->userId' 
						
						ORDER BY `tbl_booking`.`checkIn` DESC";
                    	$result = mysqli_query($GLOBALS['conn'],$sql);
							if(!$result){
								echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
								return false;
							}
						$total = 0;
						$dateBegin=time();
						$dateEnd=time();
						while ($row = mysqli_fetch_assoc($result)) {
							
						
                    ?>
                    <tr>
                        <td><em><?= $row['roomNo']?></em></td>
                        <td><em><?= $row['roomName']?></em></td>
                        <td><em><?php
                        	if($dateBegin>strtotime($row['checkIn'])){
                        		$dateBegin = strtotime($row['checkIn']);
                        	}
                        	echo $row['checkIn'];?></em></td>
                        <td><em>
                        	<?php
                        	if($dateEnd<strtotime($row['checkOut'])){
                        		$dateEnd = strtotime($row['checkOut']);
                        	}
                        	echo $row['checkOut'];?>

                        </em></td>
                        <td><em><?php
                        	$noOfDay = date('d',strtotime($row['checkOut']) - strtotime($row['checkIn']));
                        	echo $noOfDay;
                        ?></em></td>
                        <td><em><?php 
                        	if($row['currency'] == $currency[1]){
                        		echo $currency[1]." ".$row['price'];
                        		$price = $row['price'];
                        	}else {
                        		if($row['currency'] == "USD"){
                        			$row['price'] = $row['price']*$_POST['USD'];
                        		}
								if($row['currency'] == "INR"){
                        			$row['price'] = $row['price']*$_POST['INR'];
                        		}
                        		$price = $row['price']/$currency[0];
                        		echo $currency[1]." ".round($price,3);
                        	}		
                        		?></em></td>
                        <td><em><?php 
	                        echo $currency[1]." ".round($noOfDay*$price,3);
	                        $total += $noOfDay*$price;
                        ?>
                        	
                        </em></td>
                        
                    </tr>
                    <?php 
                    	}
                    ?>

                    	<?php
                    	$dateBegin = date('Y-m-d',$dateBegin);
                    	$dateEnd = date('Y-m-d',$dateEnd);
                    	$sql =  "SELECT `tbl_service`.`name`,`tbl_service`.`currency`,`tbl_order`.`quantity`,`tbl_service`.`price`  FROM `tbl_order` 
INNER JOIN `tbl_service` ON `tbl_order`.`services` = `tbl_service`.`serviceId`
INNER JOIN `tbl_user` ON `tbl_order`.`clients` = `tbl_user`.`userId` WHERE `tbl_order`.`clients` = '$user->userId' AND `tbl_order`.`datetime` BETWEEN '$dateBegin' AND '$dateEnd'";

                    	$result = mysqli_query($GLOBALS['conn'],$sql);
							if(!$result){
								echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
								return false;
							}
					
						while ($row = mysqli_fetch_assoc($result)) {
							
						
                    ?><tr>
                        <td><em>#</em></td>
                        <td><em><?= $row['name']?></em><br></td>
                        <td><em>-</em></td>
                        <td><em>-</em></td>
                        <td><em><?= $row['quantity']?></em></td>
                        <td><em><?php 
                        	if($row['currency'] == $currency[1]){
                        		echo $currency[1]." ".$row['price'];
                        		$price = $row['price'];
                        	}else {
                        		if($row['currency'] == "USD"){
                        			$row['price'] = $row['price']*$_POST['USD'];
                        		}
                        		$price = $row['price']/$currency[0];
                        		echo $currency[1]." ".round($price,3);
                        	}		
                        		?></em></td>
                        <td><em><?php 
	                        echo $currency[1]." ".round($row['quantity']*$price,3);
	                        $total += $row['quantity']*$price;
                        ?>
                        	
                        </em></td>
 </tr>
                    <?php } ?>
                   
                    <tr>
                        <td colspan="4">Amount In Words&nbsp;<br>
                            <p><em><span style="text-decoration: underline;"><?= convertNumberToWord($total) ?>only</span></em><br></p>
                        </td>
                        <td colspan="1">Total Amount<br></td>
                        <td colspan="3"><?=$currency[1]." " .round($total,3) ?></td>
                    </tr>
                    <tr>
                        <td colspan="5">Hope you had a wonderful stay. Hope to see you again. Namaste!<br></td>
                        <td colspan="3">Cashier Signature</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
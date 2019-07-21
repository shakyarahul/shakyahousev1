<?php

include 'php/config.php';
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
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
					header('Location: logout.php');
                    die;
			}else{
				header('Location: logout.php');
                    die;
			}
			$user->promote($u);
			echo "Greetings, ". $user->roles."<br />";
        }else{
            session_destroy();
            header('Location: index.php');
        }
}else{
		session_destroy();
		header('Location: index.php');
}

if(isset($_POST['btnSend'])){

    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 1;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'samitadevishakya@gmail.com';                 // SMTP username
        $mail->Password = '9841433858';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to
        $mail->CharSet = "UTF-8";

        //Recipients
        $mail->setFrom('samitadevishakya@gmail.com','Rahul');
        $mail->addAddress($_POST['emailId'], $_POST['name']);     // Add a recipient
        //$mail->addAddress('rahulshakya@hotmail.com');               // Name is optional
        $mail->addReplyTo('samitadevishakya@gmail.com', 'Rahul');
        $mail->addCC('rahulshakya@hotmail.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $_POST['subject'];
        $mail->Body    = $_POST['msg'];
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        $msg =  'Message has been sent';
        header("Location: index.php");
    } catch (Exception $e) {
        $msg = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
    }
}
include './header.php';
?>
    <div class="container">
       <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Notifications</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <?php 
                        $today = date("Y-m-d");
                        $sql = "SELECT * FROM `tbl_user`";
                        //echo $sql."<br>"; 
                        $result = mysqli_query($GLOBALS['conn'],$sql);
                            if(!$result){
                                echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
                                return false;
                            }
                        while ($row = mysqli_fetch_assoc($result)) {
                            $email = $row['emailId'];
                            $name = $row['name'];
                            if(date("m-d") == date("m-d",strtotime($row['dob']))){
                    ?>
                    <li><?=$row['name']?> have birthday today <a href="#" onclick="openCompose(event,'<?= $email ?>','Happy Birthday to you','It takes incredible customers like you to build a successful. It takes special people like you to make it all worthwhile. Have a wonderful birthday.','<?=$name?>')">Wanna wish</a></li>
                    <?php 
                            }
                        }
                    ?>
                    <?php 
                        $sql = "SELECT `tbl_user`.`userId`,`tbl_user`.`name`,`tbl_user`.`emailId`,`tbl_booking`.`checkOut` FROM `tbl_booking` JOIN `tbl_user` ON `tbl_user`.`userId` = `tbl_booking`.`clients` WHERE `tbl_booking`.`checkOut` = '$today'";
                       // echo $sql;
                        $result = mysqli_query($GLOBALS['conn'],$sql);
                            if(!$result){
                                echo "<br/>result = Error" .mysqli_error($GLOBALS['conn'])."<br />";
                                return false;
                            }
                        while ($row = mysqli_fetch_assoc($result)) {
                            $userId = $row['userId'];
                    ?>
                    <li><?=$row['name']?> is checking out view/make his bill</li>
                    <?php 
                        }
                    ?>
                </ul>
                <script type="text/javascript">
                    function openCompose(e,emailId,subject,msg,name){
                        e.preventDefault();
                        document.getElementById('emailId').value = emailId;
                        document.getElementById('subject').value = subject;
                        document.getElementById('msg').value = msg;
                        document.getElementById('name').value = name;
                        document.getElementById('compose').style.display = "initial";
                    }
                </script>
                <div class="card" id="compose" style="display: none">
                        <div class="card-header">
                            <span class="glyphicon glyphicon-remove" onclick="document.getElementById('compose').style.display='none'" style="float: right;cursor: pointer;">x</span>
                            <h5 class="mb-0" style="">New Message</h5>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="#" method="post">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><label>To</label></td>
                                            <td colspan="2"><input type="email" id="emailId" name="emailId"  class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td><label>Name</label></td>
                                            <td colspan="2"><input class="form-control" type="text" name="name" id="name"></td>
                                        </tr>
                                        
                                        <tr>
                                            <td><label>Subject</label></td>
                                            <td><input type="text" name="subject" id="subject" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><textarea name="msg" id="msg" class="form-control"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><button class="btn btn-primary" name="btnSend" type="submit">Send</button></td>
                                            <td colspan="2"><input type="file" name="attachment"></td>
                                        </tr>   
                                    </tbody>
                                </table>
                                </form>
                            </<div></div>>
                        </div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card"><a class="float-left" href="#" style="width:94px;"><img src="assets/img/profilePic/<?= $user->profilePic ?>" class="img img-rounded" style="width:94px;"></a>
                    <div class="card-body">
                        <h4 class="card-title"><?= $user->name ?></h4>
                        <p class="card-text">Greetings, welcome to shakya house management system</p><br /><a href="user.php?update_id=<?=$user->userId ?>">Edit Profile Information</a></div>
                </div>
            </div>
            <div class="col">
                <div class="datagrid" style="overflow: scroll;max-height:300px">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/activity/"),true);
                                foreach($jsonArry as $a => $v){
                                    if($v['user'] == $_SESSION['username'] && date('Y-m',strtotime($v['datetime'])) == date('Y-m',strtotime("now"))){
                                        if(date('Y-m-d',strtotime($v['datetime'])) == date('Y-m-d',strtotime("now"))){
                            ?>
                                
                            <tr>
                                <td><?= $v['datetime'] ?></td>
                                <td><?= $v['act'] ?></td>
                                <td><?= $v['user'] ?></td>
                            </tr>

                            <?php 
                                        }else {
                                            ?>
                                    
                                <tr class="hidden" style="display:none">
                                    <td><?= $v['datetime'] ?></td>
                                    <td><?= $v['act'] ?></td>
                                    <td><?= $v['user'] ?></td>
                                </tr>

                             <?php 
                            
                                        }
                                    }
                                } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><a href="#" onclick="showData(event)">Show more</a></td>
                            </tr>
                        </tfoot>
                    </table>
                    <script type="text/javascript">
                        
                        function showData(e){
                            e.preventDefault();
                            hidden =  document.getElementsByClassName('hidden')
                            for(i=0;i<hidden.length;i++){
                                hidden[i].style.display = "initial";
                            }

                        }
                    </script>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <caption>Rooms Information</caption>
                        <thead>
                            <tr>
                                <th>Room No</th>
                                <th>Occupancy</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/booking/"),true);
                                foreach($jsonArry as $a => $v){      
                                    if(strtotime($v['checkIn']) <= strtotime("today") && strtotime($v['checkOut']) >=  strtotime("today")){
                            ?>
                            <tr>
                                <td><?= $v['roomNo'] ?></td>
                                <td><?= $v['name'] ?></td>
                            </tr>
                            <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                
                <form class="custom-form" method="POST" id="generateReceipt" action="bill.php" >
                    <h1>Billing</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Client Name</label></div>
                        <div class="col-sm-6 input-column"><select class="form-control" name="userId" id="userId">
                                              <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/client/"),true);
                                
                                foreach($jsonArry as $a => $v){
                                    if($v['roles'] == "GUEST"){
                            ?>

                                <option value="<?= $v["userId"] ?>"><?= $v["name"]." - ".$v["address"] ?>
                                    <?php 
                                    } 
                                }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Currency</label></div>
                        <div class="col-sm-6 input-column">

                            <select  class="form-control" name="currency"> <option value="<?= "1-NPR" ?>">NPR 1 <?php 
                                $jsonArry = json_decode(file_get_contents("https://nrb.org.np/exportForexJSON.php"),true);
                                $usd = 0;
                                extract($jsonArry);
                                extract($Conversion);                                
                                foreach($Currency as $a => $v){
                                    
                            ?>
                                              

                                <option value="<?php
                                if($v['BaseCurrency'] == 'INR'){
                                       $v['TargetSell'] /=100;
                                    }else {
                                        echo '' ;  
                                    } 
                                    echo $v['TargetSell'] .'-'.$v['BaseCurrency'] ?>" <?php 
                                    if($v['BaseCurrency'] == "USD"){
                                        $usd = $v['TargetSell'];
                                        echo "selected";
                                    }else if($v['BaseCurrency'] == "INR"){
                                        $inr = $v['TargetSell'];
                                        echo "selected";
                                    }else{
                                        echo "" ;  
                                    } ?> ><?= $v["BaseCurrency"]." ".$v['TargetSell'] ?>
                                        <?php 
                                     
                                }

                                    ?>

                            </select>
							<input type="hidden" name="USD" value="<?=$usd?>">
							<input type="hidden" name="INR" value="<?=$inr?>">
                        </div>
						
                    </div>
                    <button class="btn btn-light submit-button" name="generate" type="submit">Generate Bill</button>
                </form>
          


            </div>
        </div>
    </div>
    <div>
        <div class="table-responsive" id="table-responsive">
                                <?php
                                    $calendar = new ReserveCalendar();
                                    echo $calendar->show();
                                ?>
        </div>
        <script>
                            loadmore("now");
                          function loadmore(a) {
                            
                                    var link = "<?php echo URI ?>/php/addon/view_reservecalendar.php";

                                    if(a=="now"){
                                       dataNext = ["<?= date('m') ?>","<?= date('Y') ?>"]; 
                                    }else{
                                        var dataNext = document.getElementById(a).value;
                                        dataNext = dataNext.split("=");
                                    }
                                    
                                            var xmlhttp;
                                            try {
                                                xmlhttp = new XMLHttpRequest;
                                            } catch (e) {
                                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
                                            }
                                            if (xmlhttp) {
                                                xmlhttp.open("GET", link+"?month="+ dataNext[0]+"&year="+ dataNext[1], true);
                                                xmlhttp.onreadystatechange = function () {
                                                    if (this.readyState == 4) {                                                        
                                                        var model = document.getElementById('table-responsive');
                                                        model.innerHTML = this.responseText;
                                                    }
                                                }
                                                xmlhttp.send(null);
                                }
                            
                            }
                        </script>
    </div>
    <?php include './footer.php' ;?>

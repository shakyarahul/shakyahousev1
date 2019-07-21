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
                    die;
			}else if($user->roles == "GUEST"){
					$user = new Client();
                    die;
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
if(isset($_POST['Send'])){
    
    extract($_POST);
    set_time_limit(10*count($userInfo));
    foreach ($userInfo as $value) {
        $cuser = explode("|", $value);

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
                $mail->addAddress($cuser[1], $cuser[0]);     // Add a recipient
                //$mail->addAddress('rahulshakya@hotmail.com');               // Name is optional
                $mail->addReplyTo('samitadevishakya@gmail.com', 'Rahul');
                $mail->addCC('rahulshakya@hotmail.com');
                //$mail->addBCC('bcc@example.com');

                //Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);    // Optional name

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = "To ".$cuser[0]."<br />".$body;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                $msg =  'Message has been sent';
                //header("Location: index.php");
            } catch (Exception $e) {
                $msg =  'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
            }

    }
    set_time_limit(60);
}



include './header.php';
?>
    <div class="container">
        <div class="row">
            <div class="col">
                <form action="mailer.php" method="post" enctype="multipart/form-data">
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="subject-input-field">Subject</label></div>
                        <div class="col-sm-6 input-column">
                            <input type="text" class="form-control" name="subject" id="subject-input-field">
                        </div>
                    </div>
                    
                <input type="text" name="search" id="searchdata" placeholder="search" onkeydown="searchData(event)">
                <div class="table-responsive" id="dynamicTbl">
                    
                    
                    <table class="table table-striped table-sm" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Password No</th>
                                <th>Address</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/client/"),true);
                             foreach($jsonArry as $a => $v){
                                if(strtotime($v['registeredDate']) >= strtotime("now")-2628000 && strtotime($v['registeredDate'])<=strtotime("today") && $v['roles'] == "GUEST"){    
                                    
                            ?>
                            <tr>
                                <td><?= $v['name']?></td>
                                <td><?= $v['address']?></td>
                                <td><?= $v['roles']?></td>
                                <td><input type="checkbox"  name="userInfo[]" value="<?=$v['name']?>|<?=$v['emailId']?>" checked="checked" /><a href="user.php?update_id=<?= $v['userId'] ?>"><i class="fa fa-edit"></i></a>
                                    <a href="user.php?delete_id=<?= $v['userId'] ?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                              <?php 
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        function searchData(e){
                                    var link = "<?php echo URI ?>/api/client/index.php";
                                    var searchdata = document.getElementById('searchdata').value;
                                            var xmlhttp;
                                            try {
                                                xmlhttp = new XMLHttpRequest;
                                            } catch (e) {
                                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
                                            }
                                            if (xmlhttp) {
                                                xmlhttp.open("GET", link+"?search="+searchdata , true);
                                                xmlhttp.onreadystatechange = function () {
                                                    if (this.readyState == 4) {                                                        
                                                        var model = document.getElementById('dynamicTbl');
                                                        model.innerHTML = this.responseText;
                                                    }
                                                }
                                                xmlhttp.send(null);
                                }
                        }
                    </script>
                </div>
                    <textarea name="body" class="form-control" placeholder="Content">
                    </textarea>
                    <input type="file" class="form-control" name="attachment">
                <input type="submit" name="Send" value="Send">
                </form>
            </div>
        </div>
    </div>
    <?php include './footer.php' ;?>
    
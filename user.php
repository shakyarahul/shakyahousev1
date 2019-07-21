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

if(isset($_POST["userQuery"])){
	extract($_POST);
	$registeredDate = date("Y-m-d",strtotime("now"));
    $file = (empty($_FILES['profilePic']['name']))?$profileImg:$_FILES['profilePic'];
    if(empty($userId)){
        $c = new Client($userId,$username,sha1($password),$name,$dob,$emailId,$address,$registeredDate,$passwordNo,$file,$mobile,"3");
        if($user->addAClient($c)){
            $msg = "Query Successful";
        }
    }else {
        $c = new Client($userId);
        $c->fetchDetail();

        $c->username = $username;
        $c->password = $password;
        $c->name = $name;
        $c->dob = $dob;
        $c->emailId = $emailId;
        $c->address = $address;
        $c->registeredDate = $registeredDate;
        $c->passwordNo = $passwordNo;
        $c->profilePic = $file;
        $c->mobile = $mobile;
        if($user->addAClient($c)){
            $msg = "Query Successful";
        }   
    }
    
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteAUser($delete_id)){
        $msg = "Delete Successful";
    }
}

//update
$c = new User();
if(isset($_GET['update_id'])){
    $c->userId = $_GET['update_id'];
    $c->fetchDetail();
}
include './header.php';
?>



    <div class="container">
        <div class="row">
            <div class="col">
                <form class="custom-form" method="POST" enctype="multipart/form-data" action="user.php" >
                    <h1>User Operations</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Name</label></div>
                        <div class="col-sm-6 input-column"><input value="<?= $c->name?>" class="form-control" type="text" name="name"><input class="form-control" value="<?= $c->userId?>" type="hidden" name="userId"></div>
                    </div>
					<input class="form-control" type="hidden" value="" name="username">
					<input class="form-control" type="hidden" value="" name="password">
					<div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Password No</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?= $c->passwordNo?>"  name="passwordNo" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="mobile-input-field">Nationality</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" id="mobile-input-field" value="<?= $c->mobile ?>"  name="mobile" type="text"></div>
                    </div>
					<div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Address</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?= $c->address?>"  name="address" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Email Id</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control"  name="emailId" value="<?= $c->emailId?>"  type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">DOB</label></div>
                        <div class="col-sm-6 input-column"><input readonly id="datepicker" type="text" value="<?= $c->dob?>" name="dob" class="form-control"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Profile Picture</label></div>
                        <div class="col-sm-6 input-column"><input type="hidden" name="profileImg" value="<?= $c->profilePic?>"><input type="file" accept="image/*;capture=camera" class="form-control"  name="profilePic"></div>
                    </div>
					<div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Username</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" type="text" value="<?= $c->username?>" name="username"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Password</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" type="password" value="<?= $c->password?>" name="password"></div>
                    </div>
                    <button class="btn btn-light submit-button" name="userQuery" type="submit">Submit Form</button></form>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col-6">
                        <input type="text" name="search" class="form-control" id="searchdata" placeholder="search" onkeydown="searchData(event)">
                    </div>
                    <div id="progressbar" class="col-6"></div>
                </div>  
                
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
                             
                        </tbody>
                    </table>
                    <script type="text/javascript">
                    
                        function searchData(e){
                                    var link = "<?php echo AjaxURI ?>/api/client/index.php";
                                    var searchdata = document.getElementById('searchdata').value;
                                            var xmlhttp;
                                            try {
                                                $( "#progressbar" ).progressbar({
                                                    value: false
                                                });
                                                xmlhttp = new XMLHttpRequest;
                                            } catch (e) {
                                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
                                            }
                                            if (xmlhttp) {
                                                xmlhttp.open("GET", link+"?search="+searchdata , true);
                                                xmlhttp.onreadystatechange = function () {
                                                    if (this.readyState == 4) {                                                        
                                                        var model = document.getElementById('dynamicTbl');
                                                        $( "#progressbar" ).progressbar({
                                                            value: true
                                                        });
                                                        model.innerHTML = this.responseText;
                                                    }
                                                }
                                                xmlhttp.send(null);
                                }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
			<div class="table-responsive" id="table-responsive">
                                <?php
                                    $calendar = new AttendanceCalendar();
                                    echo $calendar->show();
                                ?>
        </div>
        <script>
                            loadmore("now");
                          function loadmore(a) {
                                    var link = "<?php echo AjaxURI ?>/php/addon/view_attendancecalendar.php";
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
    <?php include './footer.php' ;?>
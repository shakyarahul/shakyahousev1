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
            echo "Query Successful";
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
            echo "Query Successful";
        }   
    }
    
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteAUser($delete_id)){
        echo "Delete Successful";
    }
}

//update
$c = new User();
if(isset($_GET['update_id'])){
    $c->userId = $_GET['update_id'];
    $c->fetchDetail();
}
?>



<!DOCTYPE html>


<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shakyahouse</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/styles.min.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md">
        <div class="container-fluid"><a class="navbar-brand" href="#"><img src="assets/img/logo.png" id="class" class="img-responsive"></a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse justify-content-end" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link " href="dashboard.php">Home</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="bookings.php">Bookings</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="user.php">Users</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="services.php">Services</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="rooms.php">Rooms</a></li>
                     <li class="nav-item" role="presentation"><a class="nav-link" href="roles.php">Roles</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
        </div>
        </div>
    </nav>
    <hr>
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
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Nationality</label></div>
                        <div cl ass="col-sm-6 input-column"><input class="form-control" type="text" value="<?= $c->mobile ?>"  name="mobile"></div>
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
                        <div class="col-sm-6 input-column"><input class="form-control" type="text" value="<?= $c->dob?>" name="dob"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Profile Picture</label></div>
                        <div class="col-sm-6 input-column"><input type="hidden" name="profileImg" value="<?= $c->profilePic?>"><input type="file" accept="image/*;capture=camera"  name="profilePic"></div>
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
                             
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        function searchData(e){
                                    var link = "<?php echo AjaxURI ?>/api/client/index.php";
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
    <div class="footer-basic">
        <footer>
            <div class="social"><a href="#"><i class="icon ion-social-instagram"></i></a><a href="#"><i class="icon ion-social-snapchat"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a><a href="#"><i class="icon ion-social-facebook"></i></a></div>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Home</a></li>
                <li class="list-inline-item"><a href="#">About System</a></li>
                <li class="list-inline-item"><a href="#">Pricing</a></li>
                <li class="list-inline-item"><a href="#">Copyright Policy</a></li>
            </ul>
            <p class="copyright">rahulshakya@hotmail.com © 2018</p>
        </footer>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
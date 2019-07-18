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

if(isset($_POST["bookingQuery"])){    
    extract($_POST);
    //print_r($_POST);

    if($user->bookARoomForClient($bookingId,$clients,$rooms,$checkInOut[0],$checkInOut[count($checkInOut)-1])){
       echo "Query Successful";
    }
    header("Location: bookings.php");
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteABooking($delete_id)){
        echo "Query Successful";
    }
}

//update
$c = new Booking();
if(isset($_GET['update_id'])){
    $c->bookingId = $_GET['update_id'];
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
    <script src="assets/js/jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md">
        <div class="container-fluid"><a class="navbar-brand" href="#"><img src="assets/img/logo.png" id="class" class="img-responsive"></a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse justify-content-end" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="bookings.php">Bookings</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="user.php">Users</a></li>
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
                <form class="custom-form" method="POST" action="bookings.php">
                    <h1>Booking Operations</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Client Name</label></div>
                        <div class="col-sm-6 input-column">
							<select name="clients">
								              <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/client/"),true);
                                foreach($jsonArry as $a => $v){
									if($v['roles'] == "GUEST"){
                            ?>

								<option value="<?= $v["userId"] ?>" <?= ($v["userId"] == $c->clients)?"selected":""; ?> ><?= $v["name"]." - ".$v["address"] ?>
									<?php 
									} 
								}
									?>
							</select>
							<input class="form-control" type="hidden" name="bookingId" value="<?=$c->bookingId?>">
						</div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="pawssword-input-field">Room Name</label></div>
                        <div class="col-sm-6 input-column">
                            
                            <select name="rooms" id="roomList" onchange="loadmore('now')">
                                              <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/room/"),true);
                                foreach($jsonArry as $a => $v){                                    
                            ?>

                                <option value="<?= $v["roomId"] ?>" <?= ($v["roomId"] == $c->rooms)?"selected":""; ?> ><?= $v["roomNo"]."-".$v["name"] ?>
                                    <?php 
                                     
                                }
                                    ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Check In/Check Out</label></div>
                        <div class="col-sm-6 input-column">
                            <div id="table-responsive" class="table-responsive">
                                <?php
                                    $calendar = new Calendar();
                                    echo $calendar->show();
                                ?>
                            </div>
                        </div>
                        <script>
                            loadmore("now");
                          function loadmore(a) {
                            
                                    var link = "<?php echo URI ?>/php/addon/view_calendar.php";
                                    if(a=="now"){
                                       dataNext = ["<?= date('m') ?>","<?= date('Y') ?>"]; 
                                    }else{
                                        var dataNext = document.getElementById(a).value;
                                        dataNext = dataNext.split("=");
                                    }
                                    
                                    var e = document.getElementById("roomList");
                                    var rooms = e.options[e.selectedIndex].text;
                                    rooms = rooms.split("-");
                                    
                                            var xmlhttp;
                                            try {
                                                xmlhttp = new XMLHttpRequest;
                                            } catch (e) {
                                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
                                            }
                                            if (xmlhttp) {
                                                xmlhttp.open("GET", link+"?month="+ dataNext[0]+"&year="+ dataNext[1]+"&rooms="+rooms[0]+"&update_id=<?=(isset($_GET['update_id']))?$_GET['update_id']:"" ?>" , true);
                                                xmlhttp.onreadystatechange = function () {
                                                    if (this.readyState == 4) {                                                        
                                                        var model = document.getElementById('table-responsive');
                                                        model.innerHTML = this.responseText;
                                                    }
                                                }
                                                xmlhttp.send(null);
                                }
                            
                            }

                            function checked(day){
                                document.getElementById('checked-'+day).click();
                                $color = document.getElementById('data-'+day).style.backgroundColor;
                                
                                document.getElementById('data-'+day).style.backgroundColor = 'rgb('+invert($color)+')';

                            }
                            function invert(rgb) {
                              rgb = Array.prototype.join.call(arguments).match(/(-?[0-9\.]+)/g);
                              for (var i = 0; i < rgb.length; i++) {
                                rgb[i] = (i === 3 ? 1 : 255) - rgb[i];
                              }
                              return rgb;
                            }
                        </script>
                    </div>
                    <button class="btn btn-light submit-button" type="submit" name="bookingQuery">Submit Form</button></form>
            </div>
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Name</th>
                                <th>Room</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/booking/?size=10"),true);
                                foreach($jsonArry as $a => $v){
                            ?>
                            <tr>
                                <td><?= $v['checkIn'] ?></td>
                                <td><?= $v['checkOut'] ?></td>
                                <td><?= $v['name'] ?></td>
                                <td><?= $v['roomNo'] ?></td>
                                <td><a  href="bookings.php?update_id=<?= $v['bookingId']?>"><i class="fa fa-edit"></i></a>
                                    <a href="bookings.php?delete_id=<?= $v['bookingId']?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                        <?php       
                            } ?>
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>	
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
    

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
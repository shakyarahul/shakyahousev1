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
       $msg = "Query Successful";
    }
    header("Location: bookings.php");
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteABooking($delete_id)){
        $msg = "Query Successful";
    }
}

//update
$c = new Booking();
if(isset($_GET['update_id'])){
    $c->bookingId = $_GET['update_id'];
    $c->fetchDetail();
}
include './header.php';
?>
    <div class="container">
        <div class="row">
            <div class="col">
                <form class="custom-form" method="POST" action="bookings.php">
                    <h1>Booking Operations</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Client Name</label></div>
                        <div class="col-sm-6 input-column">
							<select name="clients" class="form-control">
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
                            
                            <select class="form-control" name="rooms" id="roomList" onchange="loadmore('now')">
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
    <?php include './footer.php' ;?>
    
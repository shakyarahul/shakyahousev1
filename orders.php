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

if(isset($_POST["orderQuery"])){    
    extract($_POST);
    $registeredDate = date("Y-m-d H:i:s",strtotime("now"));
    if($user->orderServiceForClient($orderId,$clients,$services,$quantity,$registeredDate)){
       echo "Query Successful";
    }
    header("Location: orders.php");
}


//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteAOrder($delete_id)){
        echo "Delete Successful";
    }
}

//update
$c = new Order();
if(isset($_GET['update_id'])){
    $c->orderId = $_GET['update_id'];
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
                    <li class="nav-item" role="presentation"><a class="nav-link" href="bookings.php">Bookings</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="orders.php">Orders</a></li>
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
                    <form class="custom-form" action="orders.php" method="POST">
                        <h1>Order Operations</h1>
                        <div class="form-row form-group">
                            <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Client Name</label></div>
                            <div class="col-sm-6 input-column">

                                <select name="clients">
                                           <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/booking/"),true);
                                foreach($jsonArry as $a => $v){      
                                    if(strtotime($v['checkIn']) <= strtotime("today") && strtotime($v['checkOut'])+86400 >=  strtotime("today")){
                                                if($v['roles'] == "GUEST"){

                                ?>

                                    <option value="<?= $v['userId'] ?>" <?= ($c->clients == $v['userId'])?"selected":""; ?> ><?= $v["roomNo"] ?> - <?= $v["name"] ?>
                                        <?php 
                                            }
                                        } 
                                    }
                                        ?>
                            </select>

                            <input class="form-control" type="hidden" name="orderId" value="<?= $c->orderId ?>">
                        </div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Quantity</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control"  value="<?= $c->quantity?>" name="quantity" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="pawssword-input-field">Service Name</label></div>
                        <div class="col-sm-6 input-column">
                            <select name="services">
                                              <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/service/"),true);
                                foreach($jsonArry as $a => $v){
                                    ?>

                                <option value="<?= $v["serviceId"] ?>"  <?= ($c->services == $v['serviceId'])?"selected":""; ?> ><?= $v["name"]." - ".$v["currency"]." - ".$v["price"] ?>
                                    <?php 
                                }
                                    ?>
                            </select>
                        </div>
                    </div><button class="btn btn-light submit-button" name="orderQuery" type="Submit">Submit Form</button></form>
            </div>
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Client</th>
                                <th>Quantity</th>
                                <th>Room</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $jsonArry = json_decode(file_get_contents(URI."/api/order/"),true);
                            foreach($jsonArry as $a => $v){
                                if(strtotime($v['datetime']) >= strtotime("now")-(2628000/2) && strtotime($v['datetime']) <= strtotime("now") ){
                            ?>

                            <tr>
                                <td><?=$v['name'] ?></td>
                                <td><?=$v['clientName'] ?></td>
                                <td><?=$v['quantity'] ?></td>
                                <td><?=$v['roomNo'] ?></td>
                                <td><a href="orders.php?update_id=<?=$v['orderId'] ?>"><i class="fa fa-edit"></i></a><a href="orders.php?delete_id=<?=$v['orderId'] ?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                            <?php }

                            }?>
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
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

if(isset($_POST["serviceQuery"])){
    //echo "hi";
    extract($_POST);
    $c = new Service($serviceId,$name,$currency,$price,$detail);
    if($user->addAService($c)){
        echo "Query Successful";
    }
   // header("Location: services.php");
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteAService($delete_id)){
        echo "Delete Successful";
    }
}

//update
$c = new Service();
if(isset($_GET['update_id'])){
    $c->serviceId = $_GET['update_id'];
    $c->fetchDetail();
}
?><!DOCTYPE html>
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
                    <li class="nav-item" role="presentation"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="bookings.php">Bookings</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="user.php">Users</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="services.php">Services</a></li>
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
                <form class="custom-form" action="services.php" method="POST">
                    <h1>Service Operations</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Service Name</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" type="text" value="<?=$c->name ?>" name="name"><input class="form-control" type="hidden" value="<?=$c->serviceId ?>" name="serviceId"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Detail</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" name="detail" value="<?=$c->detail ?>"  type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Currency</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?=$c->currency ?>"  name="currency" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Price</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?=$c->price ?>"  name="price" type="text"></div>
                    </div><button class="btn btn-light submit-button" name="serviceQuery" type="Submit">Submit Form</button></form>
            </div>
            <div class="col">
                <input type="text" name="search" id="searchdata" placeholder="search" onkeydown="searchData(event)">
                <div class="table-responsive" id='dynamicTbl'>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Currency</th>
                                <th>Price</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/service/"),true);
                                foreach($jsonArry as $a => $v){
                                    
                            ?>
                            <tr>
                                <td><?= $v['name'] ?></td>
                                <td><?= $v['currency'] ?></td>
                                <td><?= $v['price'] ?></td>
                                <td>
                                    <a href="services.php?update_id=<?= $v['serviceId'] ?>"><i class="fa fa-edit"></i></a>
                                    <a href="services.php?delete_id=<?= $v['serviceId'] ?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                           <?php
                            }
                            ?>
                        </tbody>
                        
                    </table>

                </div><script type="text/javascript">
                        function searchData(e){
                            
                                    var link = "<?php echo AjaxURI ?>/api/service/index.php";
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
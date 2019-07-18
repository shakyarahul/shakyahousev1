<?php
    include 'php/config.php';
    //session check
    //echo $_SESSION['username'] . " " . $_SESSION["password"];
    if(isset($_SESSION["username"]) && isset($_SESSION["password"]) ){
        $user = new User("",$_SESSION["username"],($_SESSION["password"]));
        if($user->verifyLogin()){
            header("Location: dashboard.php");
        }else{
            session_destroy();
            header("Location: index.php");
        }
    }
    //submitcheck
    if(isset($_POST['login'])){
        $user = new User("",$_POST['username'],sha1($_POST['password']));
        if($user->verifyLogin()){
            header("Location: dashboard.php");
        }
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
                <form class="form-inline" action="#" method="post">
                    <div autofocus class="form-group"><input class="form-control" value="" name="username" type="text" placeholder="Username"></div>
                    <div class="form-group"><input class="form-control" name="password" value="" type="password" placeholder="Password"></div><button class="btn btn-primary" name="login" type="submit">Log In</button>
                </form>
        </div>
        </div>
    </nav>
    <hr>
    <div>
        <div class="jumbotron">
            <div id="jumbotron-box">
                <h1 class="text-center">Shakya House Management System</h1>
                <p>Records of Bookings, Orders, Roles, Rooms, Services amd Clients.</p>
                <h2>Features</h2>
                <ul>
                    <li>Loging for Staff, Clients and Administrator</li>
                    <li>Authorized and authenticated interfaces</li>
                    <li>Calendar viewer for bookings</li>
                    <li>CRUD operation on Orders, Roles, Rooms, Services, Clients, Bookings table<br></li>
                    <li>View the bill of a specified client<br></li>
                    <li>Handle occasion email handler<br></li>
                    <li>View reservation tab and detail view<br></li>
                    <li>Activity viewer<br></li>
                </ul>
                <p><a class="btn btn-primary" role="button" href="http://rahulshakya.info.np/">Learn more</a></p>
            </div>
        </div>
    </div>
    
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
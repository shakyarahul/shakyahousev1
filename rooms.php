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

if(isset($_POST["roomQuery"])){
    extract($_POST);
    $c = new Room($roomId,$name,$type,$floor,$currency,$price,$roomNo);
    if($user->addARoom($c)){
        $msg = "Query Successful";
    }
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteARoom($delete_id)){
        $msg = "Query Successful";
    }
}

//update
$c = new Room();
if(isset($_GET['update_id'])){
    $c->roomId = $_GET['update_id'];
    $c->fetchDetail();
}
include './header.php';
?>
    <div class="container">
        <div class="row">
            <div class="col">
                <form class="custom-form" action="rooms.php" method="POST">
                    <h1>Room Operations</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">Room Name</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" type="text" value="<?=$c->name ?>" name="name"><input class="form-control" value="<?=$c->roomId ?>" type="hidden" name="roomId"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Type</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control"  value="<?=$c->type ?>" name="type" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Currency</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?=$c->currency ?>" name="currency" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Price</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?=$c->price ?>" name="price" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="email-input-field">Floor</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?=$c->floor ?>" name="floor" type="text"></div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="pawssword-in value="<?=$c->name ?>"put-field">roomNo</label></div>
                        <div class="col-sm-6 input-column"><input class="form-control" value="<?=$c->roomNo ?>"  name="roomNo" type="text" ></div>
                    </div><button class="btn btn-light submit-button" name="roomQuery" type="submit">Submit Form</button></form>
            </div>
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Floor</th>
                                <th>RoomNo</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/room/"),true);
                                foreach($jsonArry as $a => $v){
                                    
                            ?>
                            <tr>
                                <td><?= $v['name']?></td>
                                <td><?= $v['currency']." ".$v['price'] ?></td>
                                <td><?= $v['floor']?></td>
                                <td><?= $v['roomNo']?></td>
                                <td><a href="rooms.php?update_id=<?= $v['roomId']?>"><i class="fa fa-edit"></i></a>

                                    <a href="rooms.php?delete_id=<?= $v['roomId']?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                            <?php 
                                    
                                }
                            ?>
                           
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include './footer.php' ;?>

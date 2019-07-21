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
        $msg = "Query Successful";
    }
   // header("Location: services.php");
}

//delete
if(isset($_GET['delete_id'])){
    extract($_GET);
    if($user->deleteAService($delete_id)){
        $msg = "Delete Successful";
    }
}

//update
$c = new Service();
if(isset($_GET['update_id'])){
    $c->serviceId = $_GET['update_id'];
    $c->fetchDetail();
}
include './header.php';
?>  <div class="container">
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
    <?php include './footer.php' ;?>
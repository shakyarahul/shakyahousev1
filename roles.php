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
					?><a href="index.php">Back</a>
					<?php
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

if(isset($_POST["roleQuery"])){    
    extract($_POST);
    //print_r($_POST);

    if($user->setRoles($userId,$roleId)){
       $msg = "Query Successful";
    }
   header("Location: roles.php");
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
                <form class="custom-form" method="POST" action="roles.php">
                    <h1>Roles Operations</h1>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="name-input-field">User Name</label></div>
                        <div class="col-sm-6 input-column">
							<select name="userId">
								              <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/client/"),true);
                                foreach($jsonArry as $a => $v){
								
                            ?>

								<option value="<?= $v["userId"] ?>" <?= ($v["userId"] == $c->userId)?"selected":""; ?> ><?= $v["name"]." - ".$v["address"]." ".$v["dob"] ?>
									<?php 
								}
									?>
							</select>
							<input class="form-control" type="hidden" name="userId" value="<?=$c->userId?>">
						</div>
                    </div>
                    <div class="form-row form-group">
                        <div class="col-sm-4 label-column"><label class="col-form-label" for="pawssword-input-field">Roles</label></div>
                        <div class="col-sm-6 input-column">
                            
                            <select name="roleId" id="roomList" onchange="loadmore('now')">
                                              <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/role/"),true);
                                foreach($jsonArry as $a => $v){                                    
                            ?>

                                <option value="<?= $v["roleId"] ?>" <?= ($v["roleId"] == $c->roles)?"selected":""; ?> ><?= $v["name"] ?>
                                    <?php 
                                     
                                }
                                    ?>
                            </select>

                        </div>
                    </div>
                    
                    <button class="btn btn-light submit-button" type="submit" name="roleQuery">Submit Form</button>
                </form>
            </div>
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>username</th>
                                <th>role</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                                $jsonArry = json_decode(file_get_contents(URI."/api/client/"),true);
                                foreach($jsonArry as $a => $v){
                                    
                            ?>
                            <tr>
                                <td><?= $v['name'] ?></td>
                                <td><?= $v['roles'] ?></td>
                                <td><a  href="roles.php?update_id=<?= $v['userId']?>"><i class="fa fa-edit"></i></a>
                                    <a href="roles.php?delete_id=<?= $v['userId']?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">&lt;&lt;</td>
                                <td>&gt;&gt;</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php include './footer.php' ;?>

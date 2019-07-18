<?php
if(isset($_GET['search'])){
	include_once("../../php/database.php");
	include_once("../../php/model/User.php");

	$user = new User();
	$result = $user->read($_GET['search']);
	$user_arr = array();
	if($result!=NULL){
		while($row = mysqli_fetch_assoc($result)){
			extract($row);
			$user = array(
				'userId' => $userId,
				'emailId' => $emailId,
				'name' => $name,
				'dob' => $dob,
				'address' => $address,
				'roles' => $roles,
				'registeredDate' => $registeredDate
			);

			array_push($user_arr, $user);
		}

		?>
			<table class="table table-striped table-sm" id="dynamicTbl">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Password No</th>
                                <th>Address</th>
                                <th><i class="fa fa-star"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php 
                            if(!empty($user_arr)){
                                foreach($user_arr as $a => $v){
                                    
                            ?>
                            <tr>
                                <td><?= $v['name']?></td>
                                <td><?= $v['address']?></td>
                                <td><?= $v['roles']?></td>
                                <td>
						<input type="checkbox" name="userInfo[]" value="<?=$v['name']?>|<?=$v['emailId']?>"  checked="checked" />
                                	<a href="user.php?update_id=<?= $v['userId'] ?>"><i class="fa fa-edit"></i></a>
                                    <a href="user.php?delete_id=<?= $v['userId'] ?>"><i class="fa fa-remove"></i></a></td>
                            </tr>
                              <?php 
                                    
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    
		<?php
	}

}else {
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');


	include_once("../../php/database.php");
	include_once("../../php/model/User.php");

	$user = new User();
	$result = $user->read();
	$user_arr = array();
	if($result!=NULL){
		while($row = mysqli_fetch_assoc($result)){
			extract($row);
			$user = array(
				'userId' => $userId,
				'name' => $name,
				'dob' => $dob,
				'address' => $address,
				'emailId'=> $emailId,
				'roles' => $roles,
				'registeredDate' =>$registeredDate
			);

			array_push($user_arr, $user);
		}

		echo json_encode($user_arr);
	}else{
		echo json_encode(array('message'=>'No User Found'));
	}
}
?>
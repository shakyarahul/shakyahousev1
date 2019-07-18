<?php
if(isset($_GET['search'])){
	include_once("../../php/database.php");
	include_once("../../php/model/Service.php");

	$service = new Service();
	$result = $service->read($_GET['search']);
	$service_arr = array();
	if($result!=NULL){
		while($row = mysqli_fetch_assoc($result)){
			extract($row);
			$service = array(
				'serviceId' => $serviceId,
				'name' => $name,
				'currency' => $currency,
				'price' => $price,
				'detail' => $detail
			);

			array_push($service_arr, $service);
		}

                            ?>
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
                                foreach($service_arr as $a => $v){
                                    
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
                        
                    </table><?php
		
	}
}else {
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');


	include_once("../../php/database.php");
	include_once("../../php/model/Service.php");

	$service = new Service();
	$result = $service->read();
	$service_arr = array();
	if($result!=NULL){
		while($row = mysqli_fetch_assoc($result)){
			extract($row);
			$service = array(
				'serviceId' => $serviceId,
				'name' => $name,
				'currency' => $currency,
				'price' => $price,
				'detail' => $detail
			);

			array_push($service_arr, $service);
		}

		echo json_encode($service_arr);
	}else{
		echo json_encode(array('message'=>'No Services Found'));
	}

}
?>
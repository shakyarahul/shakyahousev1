                             <?php 
                                $jsonArry = json_decode(file_get_contents("https://app.cloudfactory.com/schedule/calendar?user_id=9bpCWL3sLWi1cQddZHOO&start_date=2018-10-07T00:00:00+05:45&end_date=2018-10-13T23:59:59+05:45&view=Weekly"),true);
									var_dump($jsonArry);
                             
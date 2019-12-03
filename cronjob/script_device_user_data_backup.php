<?php

  //$conn= mysqli_connect("localhost", "gym_weybee", "gymweybee@123","gym_weybee");
$conn= mysqli_connect("localhost", "root", "","luzonerp");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM deviceinfo where portno='5000'";
$result = $conn->query($sql);
$result = $result->fetch_assoc();
$ipaddress = $result['ipaddress'];
$portno = $result['portno'];
$username = $result['username'];
$password =  $result['password'];


$deviceusers = "SELECT * From deviceusers ORDER BY userid DESC LIMIT 1";
$deviceusersresult = $conn->query($deviceusers);
$data=array();

while($row=mysqli_fetch_assoc($deviceusersresult)){
       $data=$row;
     }

$sms = "SELECT * From smssetting where status='1' And smsonoff='Active'";
$smsresult = $conn->query($sms);
$smsresult = $smsresult->fetch_assoc();

$device_status = "SELECT * FROM device_status where status='1' ORDER BY device_status DESC LIMIT 1";
$device_status_result = $conn->query($device_status);
$device_status_result_ex = $device_status_result->fetch_assoc();

// $apicronjobresult = $apicronjobresult->fetch_assoc();

// $deviceuserbackup = "SELECT * From deviceuserbackup ORDER BY user_id DESC LIMIT 1";
// $deviceuserbackupresult = $conn->query($deviceuserbackup);
// $deviceuserbackupresultarray=array();



// while($row=mysqli_fetch_assoc($deviceuserbackupresult)){
//        $deviceuserbackupresultarray=$row;
//      }
     // $deviceuserbackupresult = $deviceuserbackupresult->fetch_assoc();


	// if (!empty($deviceuserbackupresultarray)) {
	// 	$deviceuserbackupcount = $deviceuserbackupresultarray['user_id'];
	// }else{
	// 	$deviceuserbackupcount = 0;
	// }
     $deviceuserbackupcount = 0;
	// dd($data);

	// $connection = @fsockopen(''.$ipaddress.'', ''.$portno.'');
	// if (is_resource($connection))
	// {
	//    $sts = "connected";
	//    fclose($connection);
	// }
	// else
	// {
	//    $sts = 'Disconnected';
	// }

     
   if (!empty($device_status_result_ex['status']) == 1) {
     $sts = 'connected';
   }else{
     $sts = 'disconnected';
   }

	if (!empty($sts == 'connected')) {

		if ($data != '') {

			if ($deviceuserbackupcount != $data['userid']) {
				
					// if ($deviceuserbackupcount < $data['userid']) {
						
						for($i=1;$i<=$data['userid'];$i++)
						    {

						      $url = 'http://'.$ipaddress.':'.$portno.'';
						      
						      $api = 'http://'.$ipaddress.':'.$portno.'/device.cgi/users?action=get&user-id='.$i.'&format=xml';

				                   $ch = curl_init($url);
				                   curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
				                   curl_setopt($ch, CURLOPT_URL,$api);
				                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				                   $response = curl_exec($ch);

				                   $xml_file = simplexml_load_string($response);
				                   $json = json_encode($xml_file);
				                   $array = json_decode($json,TRUE);

				                   $arrayset = [

	                                    'user_id' => $array['user-id'],
	                                    'user_index' => $array['user-index'],
	                                    'ref_user_id' => $array['ref-user-id'],
	                                    'name' => !empty($array['name']) ? "'".$array['name']."'" : 'null',
	                                    'user_active' => $array['user-active'],
	                                    'vip' => $array['vip'],
	                                    'validity_enable' => $array['validity-enable'],
	                                    'validity_date_dd' => $array['validity-date-dd'],
	                                    'validity_date_mm' => $array['validity-date-mm'],
	                                    'validity_date_yyyy' => $array['validity-date-yyyy'],
	                                    'user_pin' => !empty($array['user-pin']) ? "'".$array['user-pin']."'" : 'null',
	                                    'card1' => "'".$array['card1']."'",
	                                    
	                                   ];

				         			$escaped_values =  implode(",",array_values($arrayset));
				 					$columns = implode(",",array_keys($arrayset));

				  					$sql = "INSERT INTO `deviceuserbackup` (".$columns.") values (".$escaped_values.")";
				  					$sqlinsert = $conn->query($sql);
						      		
						    	}
						 // }
					}else{
						echo "No data Found!";
					}	
		  	}else{
		  		echo "No data Found!";
		  	}
	}else{
		// $connection = 'Disconnected';
  //     	$msg = "Device Not connected Properly !";
 	//   	$mobileno = '8200406933';

 	// 	$u = $smsresult['url'];
 	// 	$url= str_replace('$mobileno', $mobileno, $u);
  //   	$url=str_replace('$msg', $msg, $url);
  //   	$url_send = str_replace(' ', '%20', $url);
  //   	$ch = curl_init($url_send);
  //       curl_setopt($ch, CURLOPT_URL,$url_send);
  //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //       $otpsend = curl_exec($ch);

  //   	$sql= "INSERT INTO notoficationmsgdetails (`mobileno`,`smsmsg`,`subject`,`smsrequestid`) VALUES (".$mobileno.",'".$msg."','Device Not Connected','".$otpsend."')";

  //   	$sqlinsert = $conn->query($sql);
	}
  
$conn->close();

?>
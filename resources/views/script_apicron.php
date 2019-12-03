<?php

  //$conn= mysqli_connect("localhost", "gym_weybee","gymweybee@123","gym_weybee");
  $conn= mysqli_connect("localhost", "root", "","luzon");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM deviceinfo where portno='5000'";
$result = $conn->query($sql);
$result = $result->fetch_assoc();
$ipaddress = $result['ipaddress'];
$username = $result['username'];
$password =  $result['password'];
$portno = $result['portno'];

$device_status = "SELECT * FROM device_status where status='1' ORDER BY device_status DESC LIMIT 1";
$device_status_result = $conn->query($device_status);
$device_status_result_ex = $device_status_result->fetch_assoc();

// dd($device_status_result_ex);


$apicronjob = "SELECT * From apicronjob where status='0'";
$apicronjobresult = $conn->query($apicronjob);
$data=array();




while($row=mysqli_fetch_assoc($apicronjobresult)){
       $data[]=$row;
     }
// $apicronjobresult = $apicronjobresult->fetch_assoc();

// print_r($data); echo "<br/>";

     
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
	if (!empty($data)) {
    $url = ''.$ipaddress.':'.$portno.'';
			for($i=0;$i<count($data);$i++)
			    {
			     $apicronjobid = $data[$i]['apicronjobid'];
			     $api = $data[$i]['api'];

                  $username = 'admin';
                  $password = '1234';
                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                   curl_setopt($ch, CURLOPT_URL,$api);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   $response = curl_exec($ch);
                   $response = explode('=', $response);
                   echo "<pre>";print_r($response);

                   if ($response[1] == 0 && $response[0] == "Response-Code") {
                        
                    $sql= "UPDATE apicronjob SET status='1',response_code='".$response[1]."' where apicronjobid ='".$apicronjobid."'";

                    mysqli_query($conn,$sql);

                  }else{

                  	$sql= "UPDATE apicronjob SET status='2',response_code='".$response[1]."' where apicronjobid ='".$apicronjobid."'";

                    mysqli_query($conn,$sql);

                  }
			             
			    }

				}else{
					
				}         
    	}else{

    }

$conn->close();
echo 'success';

?>
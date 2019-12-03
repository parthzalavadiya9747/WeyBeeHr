<?php
 
  //$conn= mysqli_connect("localhost", "gym_weybee","gymweybee@123","gym_weybee");
  //$conn= mysqli_connect("localhost", "admin_fitness5mumbai", "fitness5mumbai@123","admin_fitness5mumbai");
  $conn= mysqli_connect("localhost", "root", "","luzonerp");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/*$sql = "SELECT * FROM deviceinfo where portno='5000'";
$result = $conn->query($sql);
$result = $result->fetch_assoc();*/
//$ipaddress = $result['ipaddress'];
//$username = $result['username'];
//$password =  $result['password'];
$username = 'admin';
$password =  '1234';



$apicronjob = "SELECT * From apicronjob where status='0'";
$apicronjobresult = $conn->query($apicronjob);
$data=array();


while($row=mysqli_fetch_assoc($apicronjobresult)){
       $data[]=$row;
     }


     

	if (!empty($data)) {
    //$url = ''.$ipaddress.':'.$portno.'';
			for($i=0;$i<count($data);$i++)
			    {
			     $apicronjobid = $data[$i]['apicronjobid'];
			     $api = $data[$i]['api'];

                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                   curl_setopt($ch, CURLOPT_URL,$api);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   $response = curl_exec($ch);
                   $response = explode('=', $response);

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
    	
$conn->close();
echo 'success';

?>
<?php

 //$conn= mysqli_connect("localhost", "gym_weybee", "gymweybee@123","gym_weybee");
$conn= mysqli_connect("localhost", "admin_fitness5mumbai", "fitness5mumbai@123","admin_fitness5mumbai");


  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$apibackup = "select * from `apitrack` where apitrackid not in(select apitrackid from backup_apitrack)";
$result = $conn->query($apibackup);



$data=array();
while($row=mysqli_fetch_assoc($result)){
      array_push($data,$row);
     }

foreach($data as $d){
	// echo $d['apischeduleid'];
	

	$sql = "INSERT INTO `backup_apitrack` SET apitrackid = '".$d['apitrackid']."' , userid = '".$d['userid']."' , apitype ='".$d['apitype']."' ,api = '".$d['api']."' ,apiresponse = '".$d['apiresponse']."', apistatus =  '".$d['apistatus']."'";
	// echo $sql;
	
				  				
  $result = mysqli_query($conn,$sql);
	if(!$result){
		echo "error".mysqli_error($conn);
	}


}
$conn->close();
?>
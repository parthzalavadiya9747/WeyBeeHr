<?php

  //$conn= mysqli_connect("localhost", "gym_weybee","gymweybee@123","gym_weybee");
$conn= mysqli_connect("localhost", "admin_fitness5mumbai", "fitness5mumbai@123","admin_fitness5mumbai");

  $query1 = "select * from deviceinfo WHERE devicetype='independent' AND portno='5000'";
	$resultp = mysqli_query($conn,$query1);
	$rowp = mysqli_fetch_assoc($resultp);
	$ipaddress = $rowp['ipaddress'];
  $username = $rowp['username'];
  $portno = $rowp['portno'];
  $password = $rowp['password'];
  $today = date("Y-m-d");  
      
	 $enddate= "select * from apischedule where status='0'";
     $result=mysqli_query($conn,$enddate);
     $data=array();
     while($row=mysqli_fetch_assoc($result)){
       $data[]=$row;
     }



if (!empty($data)) {
       
    for($i=0;$i<count($data);$i++)
      {
    		$apischeduleid = $data[$i]['apischeduleid'];
    		$startdate = $data[$i]['startdate'];
    		$apiset = $data[$i]['apiset'];
        $userid = $data[$i]['userid'];

		if($startdate <= $today){

        $sql= "INSERT INTO apicronjob (`apiuserid`,`api`,`apitype`) VALUES (".$userid.",'".$apiset."','Expiry')";
        $sql1 = "UPDATE apischedule SET status='1' where apischeduleid ='".$apischeduleid."'";

		      mysqli_query($conn,$sql);
          mysqli_query($conn,$sql1);
       }

    }
   
  }else{

    echo 'No Data Found';echo "<br/>";
  }

  if(mysqli_error($conn))
      {
        $status = "Error";
      }
      else
      {
        $status = "Success";
      }

      echo $status;
?>
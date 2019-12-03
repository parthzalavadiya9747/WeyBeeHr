<?php

  // $conn= mysqli_connect("localhost", "gym_weybee", "gymweybee@123", "gym_weybee"); 
  //  $conn= mysqli_connect("localhost", "admin_fitness5mumbai", "fitness5mumbai@123","admin_fitness5mumbai");
  // $query='SELECT * FROM memberpackages left join member on memberpackages.userid = member.userid WHERE member.status = 1';
  // $result=mysqli_query($conn,$query);

  //    $today = date("Y-m-d"); 
  //      $enddate= "SELECT * FROM memberpackages left join member on memberpackages.userid = member.userid WHERE member.status = 1";
  //    $result=mysqli_query($conn,$enddate);
  //    $data=array();
  //    while($row=mysqli_fetch_assoc($result)){
  //      $data[]=$row;
  //    }
    
  //   for($i=0;$i<count($data);$i++)
  //   {
  //     if($data[$i]['expiredate'] < $today){
         
  //       $sql= "UPDATE memberpackages SET status='0' where expiredate ='".$data[$i]['expiredate']."'";
  //           mysqli_query($conn,$sql);
     
  //          }
  //   }
  
  //   echo "Package Expire Successfull";

   $conn= mysqli_connect("localhost", "root", "","gms_new");
  $query='SELECT *,MAX(expiredate) as maxdate FROM memberpackages where status = 1 GROUP BY userid';
  $result=mysqli_query($conn,$query);

     $today = date("Y-m-d"); 
       $enddate= "SELECT *,MAX(expiredate) as maxdate FROM memberpackages where status = 1 GROUP BY userid";
     $result=mysqli_query($conn,$enddate);
     $data=array();
     while($row=mysqli_fetch_assoc($result)){
       $data[]=$row;
     }
    
    for($i=0;$i<count($data);$i++)
    {
      if($data[$i]['maxdate'] < $today){
         
        $sql= "UPDATE member SET status='0' where userid ='".$data[$i]['userid']."'";
            mysqli_query($conn,$sql);
     
           }
    }
  
    echo "Member Expire Successfull";


?>
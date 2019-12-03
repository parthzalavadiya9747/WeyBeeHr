<?php
  $port_const = 80;
  date_default_timezone_set('Asia/Kolkata');
  //$conn= mysqli_connect("localhost", "root", "","fitnessfive_live");
  //$conn= mysqli_connect("localhost", "admin_fitness5mumbai", "fitness5mumbai@123","admin_fitness5mumbai");
  $conn= mysqli_connect("localhost", "root","","luzon");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM deviceinfo where ipaddress='192.168.1.80'";
$result = $conn->query($sql);
$result = $result->fetch_assoc();
$ipaddress = $result['ipaddress'];
$username = $result['username'];
$password =  $result['password'];
$date = date('Y-m-d H:i:s');

$device_status = mysqli_query($conn, 'SELECT * FROM device_status LIMIT 1');
if(!$device_status){

  echo mysqli_query($conn);

}

$count = mysqli_num_rows($device_status);
if($count > 0){

  $data = mysqli_fetch_assoc($device_status);
  $isup = $data['status']; 

}

$internetstatus = mysqli_query($conn, 'SELECT * FROM  internetstatus ORDER BY internetid DESC LIMIT 1');
if(!$internetstatus){

  echo mysqli_query($conn);

}

$internet_count = mysqli_num_rows($internetstatus);
if($internet_count > 0){

  $data = mysqli_fetch_assoc($internetstatus);
  $isinternetup = $data['internetstatus']; 

}else{
  $isinternetup = null;
} 



$connection = @fsockopen($ipaddress, $port_const);

if (is_resource($connection))
{
  
    $status = mysqli_query($conn ,'UPDATE  device_status SET status = 1, updated_at = "'.$date.'"');

    if(!$status){
      echo mysqli_error($conn);
    }

    $device_status = mysqli_query($conn, 'SELECT * FROM device_status LIMIT 1');
    if(!$device_status){

      echo mysqli_query($conn);

    }

    $count = mysqli_num_rows($device_status);
    if($count > 0){

      $data = mysqli_fetch_assoc($device_status);
      $isup = $data['status']; 

    }
    
      if($isinternetup != $isup){

        $internet = mysqli_query($conn, 'INSERT INTO internetstatus (internetstatus, internetdate) VALUES (1, "'.date('Y-m-d H:i:s').'")');
      }
    

   fclose($connection);
   echo 'success';
}
else
{
    mysqli_query($conn ,'UPDATE  device_status SET status = 0, updated_at = "'.$date.'"');
    $device_status = mysqli_query($conn, 'SELECT * FROM device_status LIMIT 1');
    if(!$device_status){

      echo mysqli_query($conn);

    }

    $count = mysqli_num_rows($device_status);
    if($count > 0){

      $data = mysqli_fetch_assoc($device_status);
      $isup = $data['status']; 

    }
   
      if($isinternetup != $isup){
        $internet = mysqli_query($conn, 'INSERT INTO internetstatus (internetstatus, internetdate) VALUES (0, "'.date('Y-m-d H:i:s').'")');
        if(!$internet){
          echo mysqli_error($conn);
        }
      }
    

    echo 'failure';
}


$conn->close();

?>
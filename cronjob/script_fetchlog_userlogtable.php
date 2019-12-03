<?php

  //$conn= mysqli_connect("localhost", "gym_weybee", "gymweybee@123","gym_weybee");
  $conn= mysqli_connect("localhost", "root", "","luzonerp");
  // $conn= mysqli_connect("localhost", "root","","gym");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$deviceeventlastid = "SELECT * FROM deviceevent where eventid='101' ORDER BY deviceeventid DESC LIMIT 1";
$deviceeventlastidresult = $conn->query($deviceeventlastid);
$dresult = $deviceeventlastidresult->fetch_assoc();

$sql = "SELECT * FROM deviceevent where eventid='101'";
$result = $conn->query($sql);

while($row=mysqli_fetch_assoc($result)){
       $delirdata[]=$row;
     }

$devicefetchlogs = "SELECT * FROM devicefetchlogs ORDER BY seqno DESC LIMIT 1";
$devicefetchlogsresult = $conn->query($devicefetchlogs);
$dflresult = $devicefetchlogsresult->fetch_assoc();

if (!empty($dflresult)) {
	$fno = $dflresult['seqno'];
}else{
	$fno = 1;
}
// $delirdata[count($delirdata)-1]['seqno']
if ($fno != $dresult['seqno']) {
  for($i=$fno;$i<=$dresult['seqno'];$i++)
    {
    	$sino[] = $i;	
    }

    $fsino = implode(',', $sino);
    $sql1 = "SELECT * FROM deviceevent where seqno IN (".$fsino.") AND eventid='101'";
	$result1 = $conn->query($sql1);
	while($row=mysqli_fetch_assoc($result1)){
        $dd[]=$row;
     }

     foreach ($dd as $ddd) {

      $date = date('Y-m-d',strtotime($ddd['date']));


     	$query1 = "INSERT INTO devicefetchlogs (`rollovercount`,`seqno`,`date`,`time`,`eventid`,`detail1`,`detail2`,`detail3`,`detail4`,`detail5`) VALUES ('".$ddd['rollovercount']."','". $ddd['seqno']."','".$date."','".$ddd['time']."','".$ddd['eventid']."','".$ddd['detail1']."','".$ddd['detail2']."','".$ddd['detail3']."','".$ddd['detail4']."','".$ddd['detail5']."')";
     	$result2 = $conn->query($query1);
     	while($row = $result->fetch_assoc()) {

     	}
     }   
  }else{
    echo "No Data For Process !!";
  }

?>
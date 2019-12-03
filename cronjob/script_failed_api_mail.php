<?php

  
  //$conn= mysqli_connect("localhost", "gym_weybee", "gymweybee@123","gym_weybee");
$conn= mysqli_connect("localhost", "root", "","luzonerp");

  // require '../PHPMailer/PHPMailerAutoload.php';
  require '/var/www/vhosts/gym.weybee.in/httpdocs/fitness5/PHPMailer/PHPMailerAutoload.php';

  $query1 = "select * from apicronjob WHERE status='2'";
	$resultp = mysqli_query($conn,$query1);
	
  while($rowp=mysqli_fetch_assoc($resultp)){
       $query1data[]=$rowp;
  }


if (!empty($query1data)) {
 
  for ($i=0; $i<count($query1data) ; $i++) { 

    $useridquery = "select * from users join apicronjob on apicronjob.apicronjobid = users.userid where users.userid=".$query1data[$i]['apiuserid']."";
    $uqresultp = mysqli_query($conn,$useridquery);
    $rrresult[] = $uqresultp->fetch_assoc();

    $anyuseridquery = "select * from anytimeaccessbelt join apicronjob on apicronjob.apiuserid = anytimeaccessbelt.deviceid where anytimeaccessbelt.deviceid=".$query1data[$i]['apiuserid']."";
    $anyuqresultp = mysqli_query($conn,$anyuseridquery);
    $anyrrresult[] = $anyuqresultp->fetch_assoc();

    // $sql = "UPDATE apicronjob SET status='3' where apicronjobid ='".$query1data[$i]['apicronjobid']."'";
    // mysqli_query($conn,$sql);

    $userid[] = $query1data[$i]['apiuserid'];
    // $username[] = $rrresult['username'];
    // $mobileno[] = $rrresult['usermobileno'];
  }

  $anytime = array_filter($anyrrresult);
  $rrresult = array_filter($rrresult);


    $html = '';
    $html .='<table id="t01" width="50%" border="0" cellpadding="10" cellspacing="10"  bgcolor="#F5F2EF" color="#fff">';
    $html .= '<tr>';
    $html .= '<th style="color=red">';

   if (!empty($rrresult)) {

      $html .= 'Username';
      $html .= '</th>';
      $html .= '<th align="center">';
      $html .= 'Mobileno';
      $html .= '</th>';
      $html .= '</tr>';

     foreach ($rrresult as $key => $name){
      $html .= '<tr>';
      $html .= '<td align="center">'.$name['username'].'</td>';
      $html .= '<td align="center">'.$name['usermobileno'].'</td>';
      $html .= '</tr>';
    }
  }



   if (!empty($anytime)) {
    $html1 = '';
    $html1 .='<table id="t01" width="50%" border="0" cellpadding="10" cellspacing="10"  bgcolor="#F5F2EF" color="#fff">';
    $html1 .= '<tr>';
    $html1 .= '<th style="color=red">';
    $html1 .= 'Belt_No';
    $html1 .= '</th>';
    $html1 .= '<th align="center">';
    $html1 .= 'Status';
    $html1 .= '</th>';
    $html1 .= '</tr>';

    foreach ($anyrrresult as $key => $name){
      $html1 .= '<tr>';
      $html1 .= '<td align="center">'.$name['beltno'].'</td>';
      $html1 .= '<td align="center">'.$name['beltstatus'].'</td>';
      $html1 .= '</tr>';
    }
    $html1 .= "</table>";
  }
    $html .= "</table>";

    if (!empty($rrresult) && !empty($anytime)) {
      $bodyContent = "Device Api Is Failed For Below Users And Anytime Access Belt! <br/>".$html."<br/>".$html1."";
    }else{
      if (!empty($rrresult)) {
        $bodyContent = "Device Api Is Failed For Below Users ! <br/>".$html."";
      }else if (!empty($anytime)) {
        $bodyContent = "Device Api Is Failed For Below Anytime Access Belt ! <br/>".$html1."";
      }
    }

    $mail = new PHPMailer;
    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->Host = 'mail.weybee.com';             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->Username = 'backup@weybee.com';          // SMTP username
    $mail->Password = 'Email@2019'; // SMTP password
    $mail->SMTPSecure = 'tsl';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                          // TCP port to connect to
    $mail->setFrom('gym@weybee.com', 'Fitness5 Device Api');
    $mail->addReplyTo('gym@weybee.com', 'Rohit');
    $mail->addAddress('rohit@weybee.com'); 
    $mail->isHTML(true);  // Set email format to HTML
    $subject="Device Api Failed";
    $mail->Subject = $subject;
    $mail->Body    = $bodyContent;

     if(!$mail->Send()) {
        echo "Mailer Error: ".$mail->ErrorInfo;
     } else {
        echo "Message has been sent";
     }

  }else{
    echo "No Data Found";
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
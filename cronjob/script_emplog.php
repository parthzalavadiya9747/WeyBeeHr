<?php

  $conn= mysqli_connect("localhost", "root","","luzonerp");
  
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $confirmdate = '';
  $count = 0;
  
  $employee = mysqli_query($conn, "SELECT * FROM employee WHERE status = 1");
  if(!$employee){
    echo mysqli_error($conn);
  }

  $employeecount = mysqli_num_rows($employee);
  
  if($employeecount > 0){

    while($row_employee = mysqli_fetch_array($employee, MYSQLI_ASSOC)) {
      $empid = $row_employee['employeeid'];
    

      $emplog = mysqli_query($conn, 'SELECT * FROM devicefetchlogs WHERE detail1 = "'.$empid.'" ORDER BY date ASC');

      if(!$emplog){
        echo mysqli_error($conn);
      }

      $emplogcount = mysqli_num_rows($emplog);

      if($emplogcount > 0){

      while($row_emplog = mysqli_fetch_array($emplog, MYSQLI_ASSOC)) {
        $count++;
        $lastlog = mysqli_query($conn, 'SELECT * FROM employeelog WHERE userid = "'.$empid.'" ORDER BY emplogid DESC LIMIT 1');
        if(!$lastlog){
          echo mysqli_error($conn);
        }

        $lastlogcount = mysqli_num_rows($lastlog);

        if($lastlogcount > 0){
          echo "<pre>";print_r($row_emplog);
          $datetimelog = mysqli_fetch_array($lastlog, MYSQLI_ASSOC);
          $punchdate = $datetimelog['punchdate'];
          $checkin = $datetimelog['checkin'];
          $checkout = $datetimelog['checkout'];
          $emplogid = $datetimelog['emplogid'];

          $checkindate = $punchdate.' '.$checkin;
          $checkoutdate = $punchdate.' '.$checkout;

          if(date('Y-m-d', strtotime($row_emplog['date'])) >= $punchdate){
            if(empty($checkout) && $punchdate == date('Y-m-d', strtotime($row_emplog['date'])) && date('H:i:s', strtotime($row_emplog['time']))> $checkin){
              $updatelog = mysqli_query($conn, 'UPDATE employeelog SET checkout="'.date('H:i:s', strtotime($row_emplog['time'])).'" WHERE emplogid="'.$emplogid.'"');

                  if(!$updatelog){
                    echo mysqli_error($conn);
                  }
            }else{

              if($row_emplog['date'].' '.$row_emplog['time'] > $checkindate && $row_emplog['date'].' '.$row_emplog['time'] > $checkoutdate){
        

                $insertlog = mysqli_query($conn, 'INSERT INTO employeelog (userid, punchdate, checkin, checkout) VALUES ("'.$empid.'", "'.date('Y-m-d', strtotime($row_emplog['date'])).'", "'.date('H:i:s', strtotime($row_emplog['time'])).'", null)');
                  if(!$insertlog){
                    echo mysqli_error($conn);
                  }
              }
            }
        }

          
          
        }else{

          $insertlog = mysqli_query($conn, 'INSERT INTO employeelog (userid, punchdate, checkin, checkout) VALUES ("'.$empid.'", "'.date('Y-m-d', strtotime($row_emplog['date'])).'", "'.date('H:i:s', strtotime($row_emplog['time'])).'", null)');
          $confirmdate = date('Y-m-d', strtotime($row_emplog['date']));
          if(!$insertlog){
            echo mysqli_error($conn);
          }


        }



      }

      }


    }



  } 

$conn->close();


?>
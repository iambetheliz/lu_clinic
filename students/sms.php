<?php 
include '../SMS/itext.php';
require_once '../includes/dbconnect.php';

$message = $_POST['message'];
$id = $_POST['StudentID'];

if($_POST['recipient'] == 'parent') {
  $res = "SELECT cphone FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `program` ON `students`.`program`=`program`.`program_id` WHERE `students`.`status` = 'active' AND StudentID = '".$id."'";
  $result = $DB_con->query($res);
  $row = $result->fetch_array(MYSQLI_BOTH);
  $phone = $row['phone'];
  $msg = $_POST['sender']."\n\n".$message;
  $result = itexmo($phone,$msg,"ST-SHAIR374833_X9NKY");
  if ($result == ""){
    echo "iTexMo: No response from server!!!";  
  } else if ($result == 0){
    echo "ok";
  }
  else { 
    echo "Something went wrong! <br>Error #". $result . " was encountered!";
  }
}
else {
  $res = "SELECT phone FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `program` ON `students`.`program`=`program`.`program_id` WHERE `students`.`status` = 'active' AND StudentID = '".$id."'";
  $result = $DB_con->query($res);
  $row = $result->fetch_array(MYSQLI_BOTH);
  $phone = $row['phone'];
  $msg = $_POST['sender']."\n\n".$message;
  $result = itexmo($phone,$msg,"ST-SHAIR374833_X9NKY");
  if ($result == ""){
    echo "iTexMo: No response from server!!!";  
  } else if ($result == 0){
    echo "ok";
  }
  else { 
    echo "Something went wrong! <br>Error #". $result . " was encountered!";
  }
}

?>
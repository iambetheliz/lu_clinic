<?php
  ob_start();
  require_once '../../includes/dbconnect.php';
  if(empty($_SESSION)) // if the session not yet started 
   session_start();
  
  // if session is not set this will redirect to login page
  if( !isset($_SESSION['user']) ) {
    header("Location: ../../index.php?attempt");
    exit;
  }
 
 if(isset($_POST["id"]))
  {
   foreach($_POST["id"] as $id)
   {
    $query = "UPDATE `faculty_med` SET `faculty_med`.`status` = 'deleted', date_deleted = NOW() WHERE `faculty_med`.`MedID` = '".$id."'";
    mysqli_query($DB_con, $query);
   }
  }

?>
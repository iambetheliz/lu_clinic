<?php
  include('../includes/dbconnect.php');

  if(!empty($_POST)) {
    if($_POST["StudentID"] != '')  
      { 
        $studentNo = $_POST['studentNo'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $ext = $_POST['ext'];
        $age = $_POST['age'];
        $sex = $_POST['sex'];
        $dob = $_POST['dob'];
        $civil = $_POST['civil'];
        $dept = $_POST['dept'];
        $program = $_POST['program'];
        $yearLevel = $_POST['yearLevel'];
        $sem = $_POST['sem'];
        $acadYear = $_POST['acadYear'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $cperson = $_POST['cperson'];
        $cphone = $_POST['cphone'];
        $StudentID = $_POST['StudentID'];
        $checked_by = $_POST['checked_by'];
    		
        mysqli_query($DB_con,'UPDATE `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` SET `students`.`studentNo`="'.$studentNo.'", first_name="'.$first_name.'", middle_name="'.$middle_name.'", last_name="'.$last_name.'",  ext="'.$ext.'", age="'.$age.'", sex="'.$sex.'", dob="'.$dob.'", civil="'.$civil.'", dept="'.$dept.'", program="'.$program.'", yearLevel="'.$yearLevel.'", sem="'.$sem.'", acadYear="'.$acadYear.'", address="'.$address.'", phone="'.$phone.'", cperson="'.$cperson.'", cphone="'.$cphone.'", checked_by="'.$checked_by.'" WHERE StudentID="'.$StudentID.'"');	
    }
}
?>
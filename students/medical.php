<?php
require_once '../includes/dbconnect.php';
include '../includes/date_time_diff.php';

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Information | Laguna University - Clinic | Medical Records System</title>
<link rel="icon" href="../images/favicon.ico">
<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"  />
<link rel="stylesheet" href="../assets/fonts/css/font-awesome.min.css">
<link href="../assets/css/simple-sidebar.css" rel="stylesheet" type="text/css">
<link href="../assets/css/panel-tabs.css" rel="stylesheet" type="text/css">
<link href="../assets/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
  @media print {
    thead {
      background: #eee;
      color: #666666;
      font-weight: bold;
    }    
    .print-content {
      margin-left: 50px;
      margin-right: 50px;
    }
    .signature {      
      max-width: 100%;
      margin-left: 30px;
      margin-right: 30px;
    }
    button {
      display: none;
    }
  }
</style>
</head>
<body>

<div class="container">
<div class="row">

<h3 class="page-header" align="center">
<div class="letterhead">
<img src="../images/logo.png" height="100px" align="left" />
<strong>LAGUNA UNIVERSITY</strong><br>
<small style="color: black;">
Laguna Sports Complex, Brgy. Bubukal, Santa Cruz, Laguna<br>
(049) 501-4360 or (049) 576-4359<br>
E-mail: info@lu.edu.ph
</small>
</div>
</h3>

<h3 align="center" style="margin-left: 10%;"><strong>STUDENT'S MEDICAL RECORD</strong>
<div class="btn-toolbar pull-right" role="toolbar">
  <div class="btn-group mr-2" role="group" aria-label="First group">
    <button type="button" class="btn btn-primary" onclick="javascript:window.print()" value="Print"><i class="fa fa-print"></i> Print</button>
    <a href="/LUMDRMS/students/" role="button" class="btn btn-success">Back</a>
  </div>
</div>
</h3>
<br>

<?php 
if (isset($_GET['StudentID']) && is_numeric($_GET['StudentID']) && $_GET['StudentID'] > 0) {

$StudentID = $_GET['StudentID'];
$res = "SELECT * FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `program` ON `students`.`program`=`program`.`program_id` WHERE StudentID=".$_GET['StudentID'];
$result = $DB_con->query($res);
$row = $result->fetch_array(MYSQLI_BOTH);

if(!empty($row)) { ?>


<table class="table table-bordered">
<thead class="thead">
<tr>
<th colspan="3">I. BASIC INFORMATION</th>
<th>Student No.: <?php echo $row['studentNo'];?></th>
</tr>
</thead>
<thead>  
<tr>
<th colspan="4"><strong>A. FULL NAME:</strong></th>
</tr>
</thead>
<tbody>
<tr>
<td><?php echo $row['first_name'] ;?><br>
<span class="text-muted"><small><i>First Name</i></small></span></td>
<td><?php echo $row['middle_name'] ;?><br>
<span class="text-muted"><small><i>Middle Name</i></small></span></td>
<td><?php echo $row['last_name'];?><br>
<span class="text-muted"><small><i>Last Name</i></small></span></td>
<td><?php echo $row['ext'];?><br>
<span class="text-muted"><small><i>Extended Name (e.g. Jr.)</i></small></span></td>
</tr>
<tr>
<td><label>Age:</label></td>
<td><?php echo $row['age'];?> years old</td>
<td><label>Gender:</label></td>
<td><?php echo $row['sex'];?></td>
</tr>
<tr>
<td><label>Date of Birth:</label></td>
<td><?php if (!empty($row['dob'])) echo date('F j, Y', strtotime($row['dob'])) ;?></td>
<td><label>Marital Status:</label></td>
<td><?php echo $row['stat'] ;?></td>
</tr>
<tr>
<td><label>Program:</label></td>
<td><?php echo $row['program_name'];?></td>
<td><label>Year Level:</label></td>
<td><?php echo $row['yearLevel'];?> Year</td>
</tr>
<tr>
<td><label>Semester: </label></td>
<td><?php echo $row['sem'];?> Semester</td>
<td><label>Academic Year:</label></td>
<td><?php echo $row['acadYear'];?></td>
</tr>
</tbody>
<thead>
<tr>
<td colspan="4">B. CONTACT INFORMATION</td>
</tr>
</thead>
<tr>
<td><label>Address:</label></td>
<td colspan="4"><?php echo $row['address'];?></td>
</tr>
<tr>
<td><label>Contact Person:</label></td>
<td><?php echo $row['cperson'];?></td>
<td><label>Cel/Tel No.:</label></td>
<td><?php echo $row['cphone'];?></td>
</tr>
</tbody>
</table>

<?php 
}
$StudentID = $_GET['StudentID'];
$med_res = mysqli_query($DB_con,"SELECT * FROM `students_med` WHERE StudentID = '$StudentID' AND `date_checked_up` IN (SELECT max(`date_checked_up`) FROM `students_med`)");

if ($med_res->num_rows != 0) { ?>

<table class="table table-bordered">
<thead>
  <tr>
    <th colspan="4">II. MEDICAL INFORMATION</th>
  </tr>
</thead>
<thead>
<th colspan="2">A. CURRENT SYSTEM</th>
<th colspan="2">B. MEDICAL HISTORY</th>
</thead>

<?php
// displaying records.
while ($med = $med_res->fetch_assoc()) { ?>

<tbody>
<tr>
<td colspan="2">
  <?php               
  if (!empty($med['sysRev'])) {
    echo $med['sysRev'];
  } 
  else {
    echo "No current illness.";
  }?>
</td>
<td colspan="2">
  <?php 
  if (!empty($med['medHis'])) {
    echo $med['medHis'];
  } 
  else {
    echo "None";
  }?>
</td>
</tr>
</tbody>
<thead>
<tr>
<th colspan="4"><label>C. PERSONAL AND SOCIAL HISTORY</label></th>
</tr>
</thead>
<tbody>
<?php 
echo "<tr>";
echo "<td><label>Alcoholic Drinker:</label> ".$med['drinker']."</td>";
echo "<td><label>Smoker:</label> ".$med['smoker']."</td>";
echo "<td colspan='2'><label>Drug User:</label> ".$med['drug_user']."</td>";
echo "</tr>";
?>
</tbody>
<thead>
<tr>                          
<th colspan="4"><label>D. O.B. GYNE</label></th>
</tr>
</thead>
<?php 
if ($med['mens'] == 'Not Applicable') {
echo "<tr>";
echo "<td colspan='4'>Not Applicable</td>";
echo "</tr>";
}
else {
echo "<tr>";
echo "<th>Menstrual Period:</th>
      <td colspan='3'>" .$med['mens']. "</td></tr>";
echo "<tr><th>Duration:</th>
      <td colspan='3'>" .$med['duration']. "</td>";
echo "</tr>";
}
if (empty($med['mens'])) {
echo "<tr>";
echo "<td colspan='4'>&nbsp;</td>";
echo "</tr>";
}
?>
<thead>
<th colspan="4">E. PHYSICAL EXAMINATION I</th>
</thead>
<tbody>
<tr>
<td>
  <label>Height:</label> 
  <?php if (!empty($med['height'])) {
    echo $med['height']." cm.";
  } ?> 
</td>
<td>
  <label>Weight:</label> 
  <?php if (!empty($med['weight'])) {
    echo $med['weight']." kg.";
  } ?> 
</td>
<td><label>BMI:</label> <?php echo $med['bmi'];?></td>
<td><label>Blood Pressure:</label> <?php echo $med['bp']; ?></td>
</tr>
<tr>
<td><label>Cardiac Rate:</label> <span data-toggle="tooltip" title="Beats per minute" style="cursor: pointer;"><?php if (!empty($med['cr'])) {
    echo $med['cr']." bpm.";
  } ?></span></td>
<td><label>Respirtory Rate:</label> <span data-toggle="tooltip" title="Breaths per minute" style="cursor: pointer;"><?php if (!empty($med['rr'])) {
    echo $med['rr']." bpm.";
  } ?></span></td>
<td colspan="2"><label>Temperature:</label> <?php if (!empty($med['temp'])) {
    echo $med['temp']." &#x2103;";
  } ?></td>
</tr>
</tbody>
<thead>
  <tr>
    <th colspan="4">F. PHYSICAL EXAMINATION II</th>
  </tr>
</thead>
<tbody>
  <tr>
    <th>CATEGORY</th>
    <th style="text-align: center;">NORMAL</th>
    <th colspan="2">ABNORMAL</th>
  </tr>
</tbody>
<tbody>
  <tr>
    <td><label>General Survey:</label> </td>
    <?php 
      if ($med['gen_sur'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['gen_sur'] ."</td>";
      }
    ?>
  </tr>
  <tr>
    <td><label>Skin:</label> </td>
    <?php 
      if ($med['skin'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['skin'] ."</td>";
      }
    ?>
  </tr>
  <tr>
    <td><label>HEENT:</label> </td>
    <?php 
      if ($med['heent'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['heent'] ."</td>";
      }
    ?>
  </tr>
  <tr>
    <td><label>Lungs:</label> </td>
    <?php 
      if ($med['lungs'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['lungs'] ."</td>";
      }
    ?>
  </tr>
  <tr>
    <td><label>Hear:</label> </td>
    <?php 
      if ($med['heart'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['heart'] ."</td>";
      }
    ?>
  </tr>
  <tr>
    <td><label>Abdomen:</label> </td>
    <?php 
      if ($med['abdomen'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['abdomen'] ."</td>";
      }
    ?>
  </tr>
  <tr>
    <td><label>Extremeties:</label> </td>
    <?php 
      if ($med['extreme'] == 'Normal') {
        echo "<td style='text-align:center;'><i class='fa fa-check'></i></td>";
        echo "<td colspan='2'></td>";
      }
      else {
        echo "<td></td>";
        echo "<td colspan='2'>". $med['extreme'] ."</td>";
      }
    ?>
  </tr>
</tbody>
<tbody>
  <tr>
    <td colspan="2">
      <label>CHEST X-RAY:</label>
      <?php 
        if (!empty($med['xray'])) {
          echo $row['xray'];
        }
        else {
          echo "&nbsp;";
        }
      ?>
    </td>
    <td colspan="2">
      <label>ASSESSMENT:</label>
      <?php 
        if (!empty($med['assess'])) {
          echo "Physically ".$med['assess']." at the time of examination.";
        }
        else {
          echo "&nbsp;";
        }
      ?>
    </td>
  </tr>
</tbody>
<thead>
  <tr>
    <th colspan="4">G. PLAN/RECOMMENDATION</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td colspan="4">
      <?php 
        if (!empty($med['plan'])) {
          echo $med['plan'];
        }
        else {
          echo "&nbsp;";
        }
      ?>
    </td>
  </tr>
</tbody>
</table>
<br>
<br>
<br>
<?php }

}
}
else {
  header("Location: /LUMDRMS/medical/");
}
?>
<div class="btn-toolbar certificate" role="toolbar">
<h3 align="center"><strong>MEDICAL CERTIFICATE</strong>
</h3>
</div>
<br>

<?php 

if (isset($_GET['StudentID']) && is_numeric($_GET['StudentID']) && $_GET['StudentID'] > 0) {

$StudentID = $_GET['StudentID'];
$res = "SELECT * FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `program` ON `students`.`program`=`program`.`program_id` WHERE StudentID=".$_GET['StudentID'];
$result = $DB_con->query($res);
$row = $result->fetch_array(MYSQLI_BOTH);

$query = mysqli_query($DB_con,"SELECT * FROM `students_med` WHERE StudentID = '$StudentID' AND `date_checked_up` IN (SELECT max(`date_checked_up`) FROM `students_med`)");
$med = $query->fetch_assoc();
if (!empty($med)) {
  $date = date('F j, Y', strtotime($med['date_checked_up']));
  $time = date('h:i a', strtotime($med['date_checked_up']));
}

if(!empty($row)) { ?>

<div class="print-content">
<p><label>Date: </label> <?php echo date('F j, Y');?></p>
<br /><br /><br />
<p>To whom it may concern,</p><br>
<p align="justify">This is to certify that <span style="text-decoration: underline;"><?php echo $row['first_name']." ".$row['middle_name']." ".$row['last_name']. " ".$row['ext'] ;?></span> was seen and examined on <span style="text-decoration: underline;"><?php echo $date." at ".$time;?></span> due to _________________________________________________ and was found to have <span style="text-decoration: underline;"><?php if (!empty($med['sysRev'])) { echo $med['sysRev']; } else { echo "No current illness."; }?></span> and was physically <?php echo $med['assess'];?> at the time of examination. <?php echo $med['plan'];?></p>
<br><br>
<p><label>Resolution:</label></p>
<p>_____ Return to class</p>
<p>_____ Sent home</p>
<p>_____ To hospital of choice</p>
<p>_____ Other: ______________________</p>
</div>
<br>
<br>
<br>
</div>
<div class="row">
<div class="signature">
<div class="col-lg-6 pull-left">
  <p align="center">__________________________________</p>
  <p align="center"><label>School Nurse</label></p>
</div>
<div class="col-lg-6 pull-right">
  <p align="center">__________________________________</p>
  <p align="center"><label>School Physician</label></p>
</div></div>
<?php }
}
else {
  header("Location: /LUMDRMS/medical/");
}
?>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/custom.js"></script> 

</body>
</html>
<?php ob_end_flush(); ?>
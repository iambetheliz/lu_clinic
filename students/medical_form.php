<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Medical Form | Laguna University - Clinic | Medical Records System</title>
<link rel="icon" href="../images/favicon.ico">
<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"  />
<link rel="stylesheet" href="../assets/fonts/css/font-awesome.min.css">
<link href="../assets/css/simple-sidebar.css" rel="stylesheet" type="text/css">
<link href="../assets/style.css" rel="stylesheet" type="text/css">
</head>
<style type="text/css">
  span.error {
  color: red;
}
</style>
<body>

  <!-- Navbar -->
  <?php include 'header.php'; ?>
  <!-- End of Navbar -->

  <!-- Content -->
	<div id="wrapper">

    <!-- Sidebar Menu Items -->
    <div id="sidebar-wrapper">
      <nav id="spy">
        <ul class="sidebar-nav" role="menu">                    
          <li>
            <a href="/lu_clinic"><span class="glyphicon glyphicon-dashboard"></span>&nbsp;&nbsp; Dashboard</a>
          </li>
          <li>
            <a href="/lu_clinic/calendar/"><span class="fa fa-calendar"></span>&nbsp;&nbsp; Activities</a>
          </li>
          <li class="active have-child" role="presentation">
            <a role="menuitem" data-toggle="collapse" href="#demo" data-parent="#accordion"><span class="fa fa-book"></span>&nbsp;&nbsp; Records &nbsp;&nbsp;<span class="caret"></span></a>
            <ul id="demo" class="panel-collapse collapse in">
              <li class="active">
                <a href="/lu_clinic/students/"><span class="glyphicon glyphicon-education"></span>&nbsp;&nbsp; Students</a>
              </li>
              <li>
                <a href="/lu_clinic/faculties/"><span class="fa fa-briefcase"></span>&nbsp;&nbsp; Faculties</a>
              </li>
              <li>
                <a href="/lu_clinic/medical/"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp; Medical</a>
              </li>
              <li>
                <a href="/lu_clinic/dental/"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp; Dental</a>
              </li>
            </ul>
          </li>
          <?php 
            if ($userRow['role'] === 'superadmin') {?>
            <li>
              <a href="/lu_clinic/users"><span class="fa fa-lock"></span>&nbsp;&nbsp; User Accounts</a>
            </li>
          <?php    }
          ?>
        </ul>
      </nav>
    </div>  
    <!-- End of Sidebar -->

    <!-- Start of Main Screen -->
    <div id="page-content-wrapper">
      <div class="page-content">
        <div class="container-fluid">

          <?php 
            require_once '../includes/dbconnect.php';

            $DB_con = new mysqli("localhost", "root", "", "records");

            if (isset($_GET['StudentID']) && is_numeric($_GET['StudentID']) && $_GET['StudentID'] > 0) {

              $StudentID = $_GET['StudentID'];
              $res = "SELECT * FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` WHERE StudentID=".$_GET['StudentID'];
              $result = $DB_con->query($res);
              $row = $result->fetch_array(MYSQLI_BOTH);
           
              if(!empty($row)){
          ?>
    
          <!-- Page Heading -->
          <div class="row">
            <div class="container-fluid">             
              <h1 class="page-header">Student Medical Form <span class="text-danger pull-right" id="errmsg"></span></h1>
            </div>
          </div>            
          <!-- End of Page Heading -->  

          <!-- Start of Form -->
          <form action="action.php" id="med_form" method="post" autocomplete="">

            <div class="row">
              <div class="container-fluid">
                <!-- Review of System -->
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <strong>REVIEW OF SYSTEM</strong>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Recurrent Headache"> <span class="lbl"></span>Recurrent Headache
                        </label>                         
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Blurring of Vision"> <span class="lbl"></span>Blurring of Vision
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Abdominal Pain"> <span class="lbl"></span>Abdominal Pain
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Cough and colds"> <span class="lbl"></span>Cough and colds
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Chest Pain"> <span class="lbl"></span>Chest pain
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline" title="Loss Of Consciousness" data-toggle="tooltip" data-placement="right" >
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="LOC/Seizure"> <span class="lbl"></span>LOC/Seizure
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Easy fatigability"> <span class="lbl"></span>Easy fatigability
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Easy bruisability"> <span class="lbl"></span>Easy bruisability
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Fever"> <span class="lbl"></span>Fever
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Vomiting"> <span class="lbl"></span>Vomiting
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline" title="Low Bowel Movement" data-toggle="tooltip" data-placement="right" >
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="LBM"> <span class="lbl"></span>LBM
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="sysRev_list[]" value="Dysuria"> <span class="lbl"></span>Dysuria
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" id="otherSysRevCheck" "> <span class="lbl"></span> Other 
                        </label>
                        <input type="text" class="form-control" name="sysRev_list[]" id="otherSysRev" style="display: none;">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Review of System -->

                <!-- Past Medical History -->
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <strong>MEDICAL HISTORY</strong>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Bronchial Asthma"> <span class="lbl"></span>Bronchial Asthma
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline" title="Pulmonary Tuberculosis" data-toggle="tooltip" data-placement="right">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="PTB"> <span class="lbl"></span>PTB
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Allergy"> <span class="lbl"></span>Allergy
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Hypertension"> <span class="lbl"></span>Hypertension
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline" title="Urinary Tract Infection" data-toggle="tooltip" data-placement="right" >
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="UTI"> <span class="lbl"></span>UTI
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Surgery"> <span class="lbl"></span>Surgery
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Cardiovascular DSE"> <span class="lbl"></span>Cardiovascular DSE
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Bleeding Disorder"> <span class="lbl"></span>Bleeding Disorder
                        </label>
                      </div>
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" name="medHis_list[]" value="Skin Disorder"> <span class="lbl"></span>Skin Disorder
                        </label>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-check">
                        <label class="checkbox-inline">
                          <input type="checkbox" class="form-check-input" id="otherMedHisCheck"> <span class="lbl"></span>Other
                        </label> 
                        <input type="text" class="form-control" name="medHis_list[]" id="otherMedHis" style="display: none;">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Past Medical History -->

                <!-- Personal and Social -->
            <div class="row">
              <div class="col-lg-6">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <strong>PERSONAL AND SOCIAL HISTORY</strong>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-check">
                      <table width="100%">
                        <tr>
                          <td><label class="form-check-label">Alcoholic Drinker: </label></td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="drinker" value="Yes"> <span class="lbl"></span>Yes
                            </label> 
                          </td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="drinker" value="No"> <span class="lbl"></span>No
                            </label> 
                          </td>
                        </tr>     
                        <tr>
                          <td><label class="form-check-label">Smoker: </label></td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="smoker" value="Yes"> <span class="lbl"></span>Yes
                            </label> 
                          </td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="smoker" value="No"> <span class="lbl"></span>No
                            </label> 
                          </td>
                        </tr>
                        <tr>
                          <td><label class="form-check-label">Use of Illicit Drugs: </label></td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="drug_user" value="Yes"> <span class="lbl"></span>Yes
                            </label> 
                          </td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="drug_user" value="No"> <span class="lbl"></span>No
                            </label> 
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <strong>OB/GYNE HISTORY</strong>
                    </div>
                  </div>
                  <div class="panel-body">
                    <div class="form-check">
                      <table width="100%">
                        <tr>
                          <td><label>Menstrual Cycle:</label></td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" name="mens" value="Regular"> <span class="lbl"></span>Regular
                            </label> 
                            <label class="radio-inline">
                              <input type="radio" name="mens" value="Irregular"> <span class="lbl"></span>Irregular
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="mens" value="Not Applicable"> <span class="lbl"></span>Not Applicable
                            </label>
                          </td>
                        </tr>
                        <tr>
                          <td><label>Duration:</label></td>
                          <td>
                            <select class="form-control input-sm" name="duration">
                              <option value="">Select</option>
                              <option value="2 to 4 days">2 to 4 days</option>
                              <option value="5 to 7 days">5 to 7 days</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" value="Dysmenorrhea" name="dys[]" id="dys"> <span class="lbl"></span><strong>Dysmenorrhea</strong>
                            </label> 
                          </td>
                          <td>
                            <label class="radio-inline">
                              <input type="radio" value="G" name="dys[]" class="dys" disabled > <span class="lbl"></span>G
                            </label> 
                            <label class="radio-inline">
                              <input type="radio" value="P" name="dys[]" class="dys" disabled > <span class="lbl"></span>P
                            </label>
                          </td>
                        </tr>
                      </table>  
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- End -->

            <!-- Physical Exam -->
            <div class="row">
              <div class="container-fluid">
                <div class="panel panel-success">
                  <div class="panel-heading">
                    <div class="panel-title">
                      <strong>PHYSICAL EXAMINATION</strong>
                    </div>
                  </div>
                  <div class="panel-body">

                    <div class="form-group row">
                      <div class="col-lg-3">
                        <label>Height: <small><i>(cm)</i></small></label> <span class="error pull-right" id="errHeight"></span>
                        <input type="text" class="form-control value" name="height" id="height" decimaldigits='1' /> 
                      </div>
                      <div class="col-lg-3">
                        <label>Weight: <small><i>(kg)</i></small></label>  
                        <input type="text" class="form-control value" name="weight" id="weight" decimaldigits='2' /> 
                      </div>
                      <div class="col-lg-3">
                        <label>Body Mass Index:</label> 
                        <input type="text" class="form-control" name="bmi[]" id="bmi" readonly style="cursor: not-allowed;" />
                      </div>
                      <div class="col-lg-3">
                        <label>BMI Category:</label> 
                        <input type="text" class="form-control" name="bmi[]" id="category" readonly style="cursor: not-allowed;" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-3">
                        <label>Blood Pressure: <small><i>(mmHg)</i></small></label>
                        <div class="form-inline">
                          <input type="text" style="width: 80px;" class="form-control" name="bp[]" /> = 
                          <input type="text" class="form-control" style="width: 135px;" name="bp[]" id="bp_reading" placeholder="Category" /> 
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <label>Cardiac Rate: <small><i>(beats per minute)</i></small></label>
                        <input type="text" class="form-control" name="cr"> 
                      </div>
                      <div class="col-lg-4">
                        <label>Respiratory Rate: <small><i>(breaths per minute)</i></small></label>
                        <input type="text" class="form-control" name="rr"> 
                      </div>
                      <div class="col-lg-2">
                        <label>Temperature: <small><i>(&#x2103; )</i></small></label>
                        <input type="text" class="form-control" name="temp"> 
                      </div>
                    </div>    
                    <table class="table table-bordered table-responsive">
                      <thead>
                        <tr>
                          <td></td>
                          <td>Normal</td>
                          <td>Abnormal</td>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>General Survey</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="gen_sur" value="Normal"> <span class="lbl"></span>
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="gen_sur">
                          </td>
                        </tr>
                        <tr>
                          <td>Skin</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="skin" value="Normal"> <span class="lbl"></span>&nbsp;
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="skin">
                          </td>
                        </tr>
                        <tr>
                          <td>HEENT</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="heent" value="Normal"> <span class="lbl"></span>&nbsp;
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="heent">
                          </td>
                        </tr>
                        <tr>
                          <td>Lungs</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="lungs" value="Normal"> <span class="lbl"></span>&nbsp;
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="lungs">
                          </td>
                        </tr>
                        <tr>
                          <td>Heart</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="heart" value="Normal"> <span class="lbl"></span>&nbsp;
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="heart">
                          </td>
                        </tr>
                        <tr>
                          <td>Abdomen</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="abdomen" value="Normal"> <span class="lbl"></span>&nbsp;
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="abdomen">
                          </td>
                        </tr>
                        <tr>
                          <td>Extremities</td>
                          <td>
                            <label class="checkbox-inline">
                              <input type="checkbox" class="form-check-input" name="extreme" value="Normal"> <span class="lbl"></span>&nbsp;
                            </label>
                          </td>
                          <td>
                            <input type="text" class="form-control" name="extreme">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <br>

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>Chest X ray:</label> <input type="text" class="form-control" name="xray">
                        </div>

                        <div class="form-inline">
                          <label>Assessment:</label> Physically &nbsp;<label class="radio-inline"><input type="radio" name="assess" value="fit"><strong>fit</strong></label>&nbsp;&nbsp; / <label class="radio-inline"><input type="radio" name="assess" value="fit"><strong>unfit</strong></label>&nbsp; at the same time of examination
                        </div>
                        <br>
                        <div class="form-group">
                          <label>Plan/Recommendation:</label> 
                          <input type="text" class="form-control" name="plan">
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label>School Nurse:</label>
                          <input type="text" class="form-control" name="checked_by" id="checked_by" />
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

                <div class="form-group">
                  <input type="hidden" name="studentNo" value="<?php echo $row['studentNo']; ?>"/>
                  <input type="hidden" name="StudentID" value="<?php echo $row['StudentID']; ?>"/>
                  <input type="hidden" name="action_type" value="save"/>
                  <input type="submit" class="btn btn-primary" id="save" name="btn-save" value="Save Record" />
                </div>
              <!-- End -->

              </div>
            </div>

          </form>
          <!-- End of Form -->
          <?php }}?>

        </div>  
      </div>
    </div>
    <!-- End of Main Screen -->

  </div>
  <!-- End of Content -->

  <footer class="footer">
    <div class="container-fluid">
        <p class="text-muted" align="right"><a href="http://lu.edu.ph/" target="_blank">Laguna University</a> &copy; 2017</p>
    </div>
  </footer>
    
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/custom.js"></script> 
<script src="../assets/js/form_validate_custom.js"></script> 
<script src="../assets/js/jquery.decimalize.js"></script> 
<script type="text/javascript">
$(document).ready(function() {
  //this calculates values automatically 
  bmi(); 
  $("#height, #weight").on("keydown keyup", function() {
    bmi();
    if ($("#bmi").val() <= 18.5) {
      $("#category").val('Underweight');
    }
    else if (($("#bmi").val() >= 18.5) && ($("#bmi").val() <= 24.9)) {
      $("#category").val('Normal weight');
    }
    else if (($("#bmi").val() >= 25) && ($("#bmi").val() <= 29.9)) {
      $("#category").val('Overweight');
    }
    else if ($("#bmi").val() >= 30) {
      $("#category").val('Obese');
    }
  });
});

function bmi() {
  var height = document.getElementById('height').value;
  var weight = document.getElementById('weight').value;
  var result = (parseFloat(weight) / parseFloat(height) / parseFloat(height)) * 10000;

  if (!isNaN(result)) {
    document.getElementById('bmi').value = result.toFixed(2);
  }
}
</script>
    
</body>
</html>
<?php ob_end_flush(); ?>
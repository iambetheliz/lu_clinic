<?php
  ob_start();
  require_once '../includes/dbconnect.php';
  if(empty($_SESSION)) // if the session not yet started 
   session_start();

   error_reporting(~E_NOTICE);
  
  // if session is not set this will redirect to login page
  if( !isset($_SESSION['user']) ) {
    header("Location: /lu_clinic/index.php?attempt");
    exit;
  }

  if ($DB_con->connect_errno) {
    header('Location: /lu_clinic/no_connection_error.php');
  exit();
  }

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password | Laguna University - Clinic | Medical Records System</title>
<link rel="icon" href="../images/favicon.ico">
<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"  />
<link href="../assets/fonts/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../assets/css/dashboard.css" rel="stylesheet" type="text/css">
<link href="../assets/css/simple-sidebar.css" rel="stylesheet" type="text/css">
<link href="../assets/style.css" rel="stylesheet" type="text/css">
<style type="text/css">  
label.error {
  color: indianred;
  font-weight: 500;
}
.form-control.error {
  border-color: indianred;
}
</style>
</head>
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
              <a href="/lu_clinic/calendar/"><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;&nbsp; Activities</a>
          </li>
          <li role="presentation" class="have-child">
            <a role="menuitem" data-toggle="collapse" href="#demo" data-parent="#accordion"><i class="fa fa-book" aria-hidden="true"></i>&nbsp;&nbsp; Records &nbsp;&nbsp;<span class="caret"></span></a>
            <ul id="demo" class="panel-collapse collapse">
              <li>
                  <a href="/lu_clinic/students/"><span class="glyphicon glyphicon-education"></span>&nbsp;&nbsp; Students</a>
              </li>
              <li>
                  <a href="/lu_clinic/faculties/"><span class="fa fa-briefcase"></span>&nbsp;&nbsp; Faculty and Staffs</a>
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
            <li class="active">
              <a href="/lu_clinic/users"><span class="fa fa-lock"></span>&nbsp;&nbsp; User Accounts</a>
            </li>
          <?php    }
          ?>
        </ul>
      </nav>
    </div>  
    <!-- End of Sidebar --> 

    <!-- Begin Main Screen -->
    <div id="page-content-wrapper">
      <div class="page-content">
        <div class="container-fluid"> 

			<!-- Page Heading -->
            <div class="row">
                <div class="container-fluid">
                    <h2 class="page-header">Edit Profile</h2>
                </div>
            </div> 
	
			<div class="row">
				<div class="container-fluid">	

					<form action="edit_profile.php" method = "POST" id="edit_prof_form" role="form" class="auth-form" >
					  
						<div class="form-group">
							<label>Username:</label>
							<input id="userName" type="text" value="<?php echo $userRow['userName'] ; ?>" name="userName" class="form-control" required />
						</div>

						<div class="form-group">
							<label>Firstname:</label>
							<input type="text" name="first_name" class="form-control" maxlength="50" value="<?php echo $userRow['first_name'] ; ?>" id="first_name" />
						</div>

						<div class="form-group">
							<label>Lastname:</label>
							<input type="text" name="last_name" class="form-control" maxlength="50" value="<?php echo $userRow['last_name'] ; ?>" id="last_name" autofocus />
						</div>

						<div class="form-group">
							<label>Position:</label>
							<select class="form-control" name="position" id="position" >
								<option value="<?php echo $userRow['position'] ; ?>"><?php echo $userRow['position'] ; ?></option>
								<option>Select</option>>
								<option value="School Nurse">School Nurse</option>
								<option value="School Physician">School Physician</option>
								<option value="School Dentist">School Dentist</option>
							</select>       
						</div>
							
						<hr />

						<div class="form-group">
							<label>Enter Current Password to continue:</label>
							<input id="current_password" type="text" name="current_password" class="form-control" data-minlength="5" required />
						</div>

						<div class="form-group" align="center">
							<a type="cancel" href="javascript:history.go(-1)" class="btn btn-default">CANCEL
							</a>
							<button id="edit_prof" name="edit_prof" align="middle" type="submit" class="btn btn-primary"> SAVE 
							</button>
						</div> 

					</form>

				</div>
			</div>

		</div>
	</div>
</div>
</div>
<footer class="footer">
	  <div class="container-fluid">
	      <p class="text-muted" align="right"><a href="http://lu.edu.ph/" target="_blank">Laguna University</a> &copy; 2017</p>
	  </div>
  </footer>
  
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/custom.js"></script> 
<script src="../assets/js/jquery.bootstrap-growl.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
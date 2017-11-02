<?php
  ob_start();
  require_once 'dbconnect.php';
  if(empty($_SESSION)) // if the session not yet started 
   session_start();
  
  // if session is not set this will redirect to login page
  if( !isset($_SESSION['user']) ) {
    header("Location: index.php?attempt");
    exit;
  }

  $DB_con = new mysqli("localhost", "root", "", "records");

    if ($DB_con->connect_errno) {
      echo "Connect failed: ", $DB_con->connect_error;
    exit();
    }

  // select loggedin users detail
  $res = "SELECT * FROM users WHERE userId=".$_SESSION['user'];
  $result = $DB_con->query($res);
  $userRow = $result->fetch_array(MYSQLI_BOTH);

  if (isset($_GET['loginSuccess'])) {
    $successMSG = "Hello, <strong>".ucwords($userRow['userName'])."!</strong> You have been signed in successfully!";
    header("Refresh:3;dashboard.php");
  }
    
    //Render facebook profile data
    $output = '';
    if(!empty($userRow)){
        $account = '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;'. ucwords($userRow['userName']).'&nbsp;&nbsp;<b class="caret"></b></a>';
        $logout = '<a href="logout.php?logout"><i class="glyphicon glyphicon-off">'.'</i>&nbsp;&nbsp;Logout</a>';
    }else{
        $output .= '<h3 class="alert alert-danger">Your google account does not exists in our database!<br>Redirecting to login page ...</h3>';
        header("Refresh:3; logout.php?logout");
    }

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Activities | Laguna University - Clinic | Medical Records System</title>
<link rel="icon" href="images/favicon.ico">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"  />
<link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="assets/css/simple-sidebar.css" rel="stylesheet" type="text/css">
<link href="assets/style.css" rel="stylesheet" type="text/css">
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
                <li class="active">
                    <a href="activities.php"><span class="glyphicon glyphicon-calendar"></span>&nbsp;&nbsp; Activities</a>
                </li>
                <li role="presentation" class="have-child">
                    <a role="menuitem" data-toggle="collapse" href="#demo" data-parent="#accordion"><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp; Records &nbsp;&nbsp;<span class="caret"></span></a>
                    <ul id="demo" class="panel-collapse collapse">
                        <li>
                            <a href="/lu_clinic/students/records.php"><span class="glyphicon glyphicon-education"></span>&nbsp;&nbsp; Students</a>
                        </li>
                        <li>
                            <a href="/lu_clinic/faculties/add_new.php"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp; Faculties</a>
                        </li>
                    </ul>
                </li>
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
                    <div class="col-lg-12">
                        <h1 class="page-header">Calendar Activities</h1>
                    </div>
                </div>  
                <div class="alert alert-warning" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <p>Displaying total numbers of patients who visited per day, week, month and year</p>
                </div>
                <!-- End of Page Heading -->
                <!-- Additionals -->
                <div class="row">
                  <div class="col-lg-12">
                    <h3>Insert badges/charts here ...</h3>
                  </div>
                </div>
                <!-- End -->
            </div>  
          </div>
        </div>
        <!-- End of Main Screen -->
  
  </div>
  <!-- End of Content -->

  <footer class="footer">
    <div class="container-fluid">
        <p class="text-muted" align="right"><a href="http://lu.edu.ph/" target="_blank">Laguna University</a> &copy; <?php echo date("Y"); ?></p>
    </div>
  </footer>
    
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/index.js" type="text/javascript"></script>
  <script type="text/javascript">
    window.setTimeout(function() {
      $(".success-login").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
      });
    }, 4000);
  </script>
    
</body>
</html>
<?php ob_end_flush(); ?>
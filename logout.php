<?php
  require_once 'includes/dbconnect.php';
  session_start();
	
	if (!isset($_SESSION['user'])) {
		header("Location: index.php?attempt");
	} else if(isset($_SESSION['user'])!="") {
		header("Location: dashboard.php?loginSuccess");
	}
	
	if (isset($_GET['logout'])) {
		unset($_SESSION['user']);
		session_unset();
		session_destroy();
		$sql = mysqli_query($DB_con,'UPDATE `users` SET `login_date` = now()');
		header("Location: /LUMDRMS");
		exit;
	}
?>
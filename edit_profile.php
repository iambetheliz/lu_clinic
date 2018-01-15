<?php
	ob_start();
  require_once 'includes/dbconnect.php';
  if(empty($_SESSION)) // if the session not yet started 
   session_start();

   error_reporting(~E_NOTICE);
  
  // if session is not set this will redirect to login page
  if( !isset($_SESSION['user']) ) {
    header("Location: index.php?attempt");
    exit;
  }

  if ($DB_con->connect_errno) {
    header('Location: /lu_clinic/no_connection_error.php');
  exit();
  }

  // select loggedin users detail
  $res = "SELECT * FROM users WHERE userId=".$_SESSION['user'];
  $result = $DB_con->query($res);
  $userRow = $result->fetch_array(MYSQLI_BOTH);

  if (isset($_GET['loginSuccess'])) {
    $successMSG = "Hello, <strong>".ucwords($userRow['userName'])."!</strong> You have been signed in successfully!";
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

	$error = false;

	if ( isset($_POST['btn-signup']) ) {
		
		// clean user inputs to prevent sql injections
		$name = trim($_POST['userName']);
		$userName = strip_tags($userName);
		$userName = htmlspecialchars($userName);
		
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		
		// basic userName validation
		if (empty($userName)) {
			$error = true;
			$userNameError = "Please enter your username.";
		} else if (strlen($userName) < 6) {
			$error = true;
			$userNameError = "Username must have atleat 6 characters.";
		} else if (!preg_match("/^[a-zA-Z0-9 ]+$/",$userName)) {
			$error = true;
			$userNameError = "Username must contain alphabets and numbers only.";
		} else {
			// check email exist or not
			$query = "SELECT userName FROM users WHERE userName='$userName'";
			$result = $DB_con->query($query);
			if($result->num_rows != 0){
				$error = true;
				$userNameError = "Username is already in use.";
			}
		}
		
		//basic email validation
		if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		} else {
			// check email exist or not
			$query = "SELECT userEmail FROM users WHERE userEmail='$email'";
			$result = $DB_con->query($query);
			if($result->num_rows != 0){
				$error = true;
				$emailError = "Provided Email is already in use.";
			}
		}
		// password validation
		if (empty($pass)){
			$error = true;
			$passError = "Please enter password.";
		} else if(strlen($pass) < 6) {
			$error = true;
			$passError = "Password must have atleast 6 characters.";
		}
		
		// password encrypt using SHA256();
		$password = hash('sha256', $pass);
		
		// if there's no error, continue to signup
		if( !$error ) {
			
			$stmt = mysqli_query($DB_con,"UPDATE users SET userName = '$userName', userEmail = '$email' AND position = '$position' WHERE userId=").$_SESSION['user'];
				
			if ($stmt) {
				unset($userName);
				unset($email);
				unset($pass);
			}	
				
		}	
		
	}
?>
	
	<div class="panel panel-success">
		<div class="panel-body">
	    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete />
	    	<fieldset>
				<div class=""> 
	            
		            <?php
					if ( isset($errMSG) ) {
						
						?>
						<div class="form-group">
			            	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
							<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
			                </div>
		            	</div>
		                <?php
					}
					?>
		            
		            <div class="form-group">
		            	<label>Username:</label>
		            	<input type="text" name="userName" class="form-control" maxlength="50" value="<?php echo $userName ?>" autofocus />
		                <span class="text-danger"><?php echo $userNameError; ?></span>
		            </div>
		            
		            <div class="form-group">
		            	<label>Email:</label>
		            	<input type="email" name="email" class="form-control" maxlength="40" value="<?php echo $email ?>" />
		                <span class="text-danger"><?php echo $emailError; ?></span>         
		            </div>
		            
		            <div class="form-group">
		            	<label>Password:</label>
		            	<input type="password" name="pass" class="form-control"  maxlength="15" />
		                <span class="text-danger"><?php echo $passError; ?></span>
		            </div>
		            
		            <div class="form-group">
		            	<hr />
		            </div>
		            
		            <div class="form-group">
		            	<button type="submit" class="btn btn-success" name="btn-signup">Add User</button>
		            </div>
		        
		        </div>
		    </fieldset>   
	    </form> 
	</div>  </div>
<?php ob_end_flush(); ?>
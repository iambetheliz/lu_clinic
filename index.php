<?php
  ob_start();
  require_once 'includes/dbconnect.php';
  if(empty($_SESSION)) // if the session not yet started 
   session_start();
  
  // it will never let you open index(login) page if session is set
  if (isset($_SESSION['user'])!="" ) {
    header("Location: dashboard.php");
  exit;
  }

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laguna University - Clinic | Medical Records System</title>
<link rel="icon" href="images/favicon.ico">
<link href="assets/fonts/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="assets/style.css" type="text/css" />
<style type="text/css">  
.auth-form {
  margin-top: 60px;
}
a {
  color: #337ab7;
  text-decoration: unset;
  color: green;
}
body {
  background-image: url(images/lu-main.jpg);
  background-repeat: no-repeat;
  background-size: cover;
}
label.error {
  color: indianred;
  font-weight: 500;
}
.form-control.error {
  border-color: indianred;
}
.password{
    position: relative;
}

.password input[type="password"]{
    padding-right: 30px;
}

.password .glyphicon,#password2 .glyphicon {
    display:none;
    right: 15px;
    position: absolute;
    top: 35px;
    cursor:pointer;
}
</style>
</head>
<body>

<!-- Main Screen -->
<div class="container">

  <!-- Login Form -->
  <div class="row">      
    <div class="auth-form">

      <div class="well well-lg">
        <table class="table no-border">
          <tr>
            <td><a href="/LUMDRMS"><img class="profile-img" src="images/logo.png" alt=""></a></td>
            <td align="center"><h3 style="padding-bottom: 10px;">Laguna University Clinic</h3></td>
          </tr>
        </table>
        <div id="error">
        <!-- error will be shown here ! -->
        </div>
        <div class="panel panel-success">
          <div class="panel-body">
            <form class="form-signin" id="login-form" method="post" autocomplete />
              <fieldset>          
              <div class="form-group">
                <label>Username</label>
                <input type="text" name="name" id="username" class="form-control" maxlength="40" value="<?php if(isset($_COOKIE["user"])) { echo $_COOKIE["user"]; } ?>" autofocus required />
                <small><span class="text-danger" id="name_error"></span></small>
              </div>
            
              <div class="form-group password">
                <label>Password</label>
                <input type="password" name="pass" id="password" class="form-control" required />
                <span class="glyphicon glyphicon-eye-open"></span>
                <small><span class="text-danger" id="pass_error"></span></small>
              </div>

              <div class="form-check">
                <label class="checkbox-inline">
                  <input type="checkbox" class="form-check-input" name="remember" id="chkShow"/><span class="lbl"></span> Remember Password
                </label>
              </div><br>
            
              <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg btn-block" name="btn-login" id="btn-login">Sign In</button>
              </div>
              </fieldset>   
            </form> 
          </div>
        </div>
        <div class="alert alert-success">
          <p align="center" style="font-weight: bold;"><a href="http://lu.edu.ph/" target="_blank">Laguna University</a> &copy; 2017</p>
        </div>
      </div>

    </div>
  </div>
  <!-- End of Login Form -->

</div>
<!-- End of Main Screen -->

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/jquery.bootstrap-growl.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
<script src="login.js"></script>
<script type="text/javascript">
  $("#password").on("keyup",function(){
    if($(this).val())
        $(".glyphicon-eye-open").show();
    else
        $(".glyphicon-eye-open").hide();
    });
  $(".glyphicon-eye-open").mousedown(function(){
    $("#password").attr('type','text');
  }).mouseup(function(){
    $("#password").attr('type','password');
  }).mouseout(function(){
    $("#password").attr('type','password');
  });
</script>

</body>
</html>
<?php ob_end_flush(); ?>
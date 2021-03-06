<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="403 Forbidden">
<title>403 Forbidden</title>
<!-- Bootstrap core CSS -->
<link rel="icon" href="images/favicon.ico">
<!-- Bootstrap core CSS -->
<link rel="stylesheet" href="assets/fonts/css/font-awesome.min.css">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"  />
<link href="assets/css/simple-sidebar.css" rel="stylesheet" type="text/css" />
<link href="assets/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="error_pages/errors.css" />
</head>
<body onload="javascript:loadDomain();">

<div id="wrapper">

<!-- Begin Main Screen -->
  <div id="page-content-wrapper">
    <div class="page-content">
      <!-- Error Page Content -->
      <div class="row">
        <div class="container">
          <div class="jumbotron">
            <h2><i class="fa fa-ban text-danger"></i> <strong>Access Forbidden</strong></h2><hr>
            <p class="lead">You do not have access permissions for that page.</p>
            <br>
            <a href="javascript:history.go(-1)" class="btn btn-lg btn-warning"><span class="maroon">Go back to previous page</span></a>
          </div>
          <div class="center-block">
            <img class="profile-img" src="images/logo.png" alt="LU logo" />
          </div>
        </div>
      </div>
      <!-- End Error Page Content -->
    </div>
  </div>
<!-- End of Mainscreen -->

</div>
<footer class="footer">
  <div class="container-fluid">
    <p align="center">Laguna University &copy; 2017</p>
  </div>
</footer>

<!--Scripts-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
  function loadDomain() {
    var display = document.getElementById("display-domain");
    display.innerHTML = document.domain;
  }
</script>
</body>
</html>
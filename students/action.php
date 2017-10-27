<?php
  ob_start();
  require_once '../dbconnect.php';
  if(empty($_SESSION)) // if the session not yet started 
   session_start();
  
  // if session is not set this will redirect to login page
  if( !isset($_SESSION['user']) ) {
    header("Location: ../index.php?attempt");
    exit;
  }

  $DB_con = new mysqli("localhost", "root", "", "records");

    if ($DB_con->connect_errno) {
      echo "Connect failed: ", $DB_con->connect_error;
    exit();
    }

  $error = false;

if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {

	if($_REQUEST['action_type'] == 'add'){

  		$studentNo = $_POST['studentNo'];
  		$last_name = $_POST['last_name'];
  		$first_name = $_POST['first_name'];
  		$middle_name = $_POST['middle_name'];
      $ext = $_POST['ext'];
  		$age = $_POST['age'];
  		$sex = $_POST['sexOption'];
  		$program = $_POST['program'];
  		$yearLevel = $_POST['yearLevel'];
  		$sem = $_POST['semOption'];
  		$acadYear = implode('-', $_POST['acadYears']);
  		$address = $_POST['address'];
  		$cperson = $_POST['cperson'];
  		$cphone = $_POST['cphone'];
  		$tphone = $_POST['tphone'];

  		if (empty($cphone)) {
  			$cphone = 'none';
  		}

  		if (empty($tphone)) {
  			$tphone = 'none';
  		}

      //checkbox
      $sysRev = implode(', ', $_POST['sysRev_list']);
      $medHis = implode(', ', $_POST['medHis_list']);
      $drinker = $_POST['drinker'];
      $smoker = $_POST['smoker'];
      $drug_user = $_POST['drug_user'];
      $weight = $_POST['weight'];
      $height = $_POST['height'];
      $bmi = $_POST['bmi'];
      $bp = $_POST['bp'];
      $cr = $_POST['cr'];
      $rr = $_POST['rr'];
      $t = $_POST['t'];
      $xray = $_POST['xray'];
      $assess = $_POST['assess'];
      $plan = $_POST['plan'];

      $med = $_POST['med'];
      $dent = $_POST['dent'];

      $StudentID = $_POST['StudentID'];
      $MedID = $_POST['MedID'];
      $StatsID = $_POST['StatsID'];

      // if there's no error, continue to signup
  		if( !$error ) {

        $query1 = "INSERT INTO students(studentNo,last_name,first_name,middle_name,ext,age,sex,program,yearLevel,sem,acadYear,address,cperson,cphone,tphone) VALUES('$studentNo','$last_name','$first_name','$middle_name','$ext','$age','$sex','$program','$yearLevel','$sem','$acadYear','$address','$cperson','$cphone','$tphone')";
        $query2 = "INSERT INTO students_med(sysRev,medHis,drinker,smoker,drug_user,weight,height,bmi,bp,cr,rr,t,xray,assess,plan,studentNo) VALUES('" . $sysRev . "','$medHis','$drinker','$smoker','$drug_user','$weight','$height','$bmi','$bp','$cr','$rr','$t','$xray','$assess','$plan','$studentNo')";
        $query3 = "INSERT INTO students_stats(med,dent,studentNo) VALUES('$med','$dent','$studentNo')";

  			$stmt1 = $DB_con->prepare($query1);
        $stmt2 = $DB_con->prepare($query2);
        $stmt3 = $DB_con->prepare($query3);

   			$stmt1->bind_param($studentNo,$last_name,$first_name,$middle_name,$ext,$age,$sex,$program,$yearLevel,$sem,$acadYear,$address,$cperson,$cphone,$tphone);
        $stmt2->bind_param($sysRev,$medHis,$drinker,$smoker,$drug_user,$weight,$height,$bmi,$bp,$cr,$rr,$t,$xray,$assess,$plan);
        $stmt3->bind_param($med,$dent);

   			if (!$stmt1 || !$stmt2 || !$stmt3){
          header("Location: medical_form.php?error");
   			} else {
          BEGIN;
      			$stmt1->execute();
            $stmt2->execute();
            $stmt3->execute();
          COMMIT;
        			header("Location: tbl_rec.php?success");
  			} 
  		}
	}
	elseif($_REQUEST['action_type'] == 'edit'){
		if(!empty($_POST['StudentID'])){
			  $studentNo = $_POST['studentNo'];
  			$last_name = $_POST['last_name'];
  			$first_name = $_POST['first_name'];
  			$middle_name = $_POST['middle_name'];
  			$age = $_POST['age'];
  			$sex = $_POST['sexOption'];
  			$program = $_POST['program'];
  			$yearLevel = $_POST['yearLevel'];
  			$sem = $_POST['semOption'];
  			$acadYear = $_POST['acadYear'];
  			$address = $_POST['address'];
  			$cperson = $_POST['cperson'];
  			$cphone = $_POST['cphone'];
  			$tphone = $_POST['tphone'];

        if (empty($studentNo)) {
          $studentNo = 'none';
        }

  			if (empty($cphone)) {
  				$cphone = 'none';
  			}

  			if (empty($tphone)) {
  				$tphone = 'none';
  			}

        // if everything is fine, update the record in the database
        if ($stmt = $DB_con->prepare("UPDATE students SET last_name = ?, first_name = ? WHERE StudentID=?")) {
          $stmt->bind_param($firstname, $lastname, $StudentID);
          $stmt->execute();
          $stmt->close();
        }
        // show an error message if the query has an error
        else {
          echo "ERROR: could not prepare SQL statement.";
        }
		}
	}
	elseif($_REQUEST['action_type'] == 'delete'){
		if(!empty($_GET['StudentID'])){

      if( !$error ) {
        $stmt = $DB_con->prepare("DELETE FROM students WHERE StudentID =?");
        $stmt->bind_param('i', $_GET['StudentID']);

        if (!$stmt){
            $errMSG = "Something went wrong, try again later..."; 
        } else {
          $stmt->execute();
          header("Location: tbl_rec.php?deleteSuccess");
        }
      }
    }
    else {
      // if the 'StudentID' variable isn't set, redirect the user
      header("Location: tbl_rec.php?deleteError");
    }
	}
}

?>
<?php ob_end_flush(); ?>
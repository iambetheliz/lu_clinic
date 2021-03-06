<?php
//Include database configuration file
include('../includes/dbconnect.php');
include '../includes/date_time_diff.php';
//Include pagination class file
include('../includes/Pagination.php');

if(isset($_POST['page'])){
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 5;
    
    //set conditions for search
    $whereSQL = $orderSQL = '';
    $keywords = $_POST['keywords'];
    $sortBy = $_POST['sortBy'];
    $dept = $_POST["dept_id"];
    $stats = $_POST["stats"];
    $archive = $_POST["archive"];
    $count = $_POST['num_rows'];

    //For number of rows per page
    if ( $count ){
      $limit = $_POST['num_rows']; 
    }

    //For keywords
    if ( !empty($keywords) ) {
      $whereSQL = " WHERE CONCAT(last_name LIKE '%".$keywords."%' or first_name LIKE '%".$keywords."%' or middle_name LIKE '%".$keywords."%' or ext LIKE '%".$keywords."%' or `faculties`.`facultyNo` LIKE '%".$keywords."%') ";
    }
    if ( !empty($keywords) && !empty($dept) ) {
      $whereSQL = " WHERE CONCAT(last_name LIKE '%".$keywords."%' or first_name LIKE '%".$keywords."%' or middle_name LIKE '%".$keywords."%' or ext LIKE '%".$keywords."%' or `faculties`.`facultyNo` LIKE '%".$keywords."%') AND dept = '".$dept."' ";
    }
    if ( !empty($keywords) && !empty($stats) ) {
      $whereSQL = " WHERE CONCAT(last_name LIKE '%".$keywords."%' or first_name LIKE '%".$keywords."%' or middle_name LIKE '%".$keywords."%' or ext LIKE '%".$keywords."%' or `faculties`.`facultyNo` LIKE '%".$keywords."%') AND CONCAT(med = '".$stats."' OR dent = '".$stats."') ";
    }

    //For depts
    if ( !empty($dept) ) {
      $whereSQL = " WHERE dept = '".$dept."' ";
    }
    if ( !empty($dept) && !empty($keywords) ) {
      $whereSQL = " WHERE dept = '".$dept."' AND CONCAT(last_name LIKE '%".$keywords."%' or first_name LIKE '%".$keywords."%' or middle_name LIKE '%".$keywords."%' or ext LIKE '%".$keywords."%') ";
    }
    if ( !empty($dept) && !empty($stats) ) {
      $whereSQL = " WHERE dept = '".$dept."' AND CONCAT(med = '".$stats."' OR dent = '".$stats."') ";
    }

    //For Med/Dental Status
    if ( !empty($stats) ) {
      $whereSQL = " WHERE CONCAT(med = '".$stats."' OR dent = '".$stats."') ";
    }
    if ( !empty($stats) && !empty($keywords) ) {
      $whereSQL = " WHERE CONCAT(med = '".$stats."' OR dent = '".$stats."') AND CONCAT(last_name LIKE '%".$keywords."%' or first_name LIKE '%".$keywords."%' or middle_name LIKE '%".$keywords."%' or ext LIKE '%".$keywords."%') ";
    }
    if ( !empty($stats) && !empty($dept) ) {
      $whereSQL = " WHERE CONCAT(med = '".$stats."' OR dent = '".$stats."') AND dept = '".$dept."' ";
    }

    //For showing/hiding deleted rows   
    if ( !empty($archive) ) {
      $whereSQL .= " AND CONCAT(`faculties`.`status` = '".$archive."' OR `faculties`.`status` = '".$archive."') ";
    }
    elseif ( empty($archive) ) {
      $whereSQL .= " AND CONCAT(`faculties`.`status` = 'active' OR `faculties`.`status` = 'deleted') ";
    }

    if ( !empty($sortBy) ){
      $whereSQL .= " ORDER BY last_name ".$sortBy;
    }
    elseif ( !empty($sortBy) && !empty($archive) ){
      $whereSQL .= " ORDER BY date_deleted ".$sortBy;
    } 
    elseif ( !empty($sortBy) && !empty($dept) ) {
      $whereSQL .= " ORDER BY last_name ".$sortBy;
    }
    elseif (empty($dept) || empty($sortBy)) {
      $whereSQL .= " ORDER BY modified DESC ";
    }

    //get number of rows
    $queryNum = $DB_con->query("SELECT COUNT(*) as postNum FROM `faculty_stats` JOIN `faculties` ON `faculties`.`facultyNo`=`faculty_stats`.`facultyNo` JOIN `department` ON `faculties`.`dept`=`department`.`dept_id` ".$whereSQL.$orderSQL);
    $resultNum = $queryNum->fetch_assoc();
    $rowCount = $resultNum['postNum'];

    //initialize pagination class
    $pagConfig = array(
        'currentPage' => $start,
        'totalRows' => $rowCount,
        'perPage' => $limit,
        'link_func' => 'searchFilter'
    );
    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $query = $DB_con->query("SELECT *, CONCAT(last_name,', ',first_name,' ',middle_name,' ',ext) AS full_name FROM `faculty_stats` JOIN `faculties` ON `faculties`.`facultyNo`=`faculty_stats`.`facultyNo` JOIN `department` ON `faculties`.`dept`=`department`.`dept_id` $whereSQL $orderSQL LIMIT $start,$limit");
    
    if($query->num_rows > 0){ ?>
    <div class="row">
      <div class="container-fluid">
        <form method="post" name="frm">
          <span class="pull-right"><strong class="text-success">Total no. of rows: <?php echo $rowCount;?></strong></span>
          <br>
          <div class="table-responsive">
          <table class="table  table-striped table-bordered" id="myTable">
            <thead>
              <tr>
                <th><label class="checkbox-inline"><input type="checkbox" class="select-all form-check-input" /><span class="lbl"></span></th>
                <th>No.</th>
                <th>Dental</th>
                <th>Medical</th>
                <th>Name</th>
                <th>Faculty No.</th>
                <th>Department</th>   
                <th>Date Added</th>     
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr id="overlay" style="display: none;">
                <td colspan="13" align="center">
                  <p>Loading records <i class="fa fa-refresh fa-spin"></i></p>
                </td>
              </tr>
            <?php
              while($row = $query->fetch_assoc()){ 
              $start++; ?>
              <tr id="table-row-<?php echo $row["StatsID"]; ?>" class="faculties">
                <td>
                  <label class="checkbox-inline"><input type="checkbox" name="chk[]" id="check" class="chk-box form-check-input" value="<?php echo $row['FacultyID']; ?>"  /> <span class="lbl"></span></label>
                </td>
                <td><?php echo $start;?></td>
                <td><?php echo $row['dent']; ?></td>
                <td><?php echo $row['med']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['facultyNo']; ?></td>
                <td><?php echo $row['dept_name']; ?></td>
                <td><?php echo date('F j, Y', strtotime($row['date_registered']));?></td>
                <td>
                  <?php 
                    if ($row['status'] == 'deleted') { 
                      ?>
                      <button type="button" name="restore" class="btn btn-default" id="restore" value="<?php echo $row['FacultyID']; ?>"><i class="fa fa-undo"></i> Restore</button>
                      <?php 
                    }
                    else { 
                      ?>
                      <div class="btn-toolbar action" role="toolbar">
                        <a href="profile.php?FacultyID=<?php echo $row['FacultyID']; ?>" class="btn btn-sm btn-warning" title="Profile" data-toggle="tooltip" data-placement="top"> <i class="glyphicon glyphicon-user"></i></a><a class="btn btn-sm btn-primary" title="Edit" data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['FacultyID']; ?>" id="getUser"> <i class="fa fa-pencil"></i></a><a class="btn btn-danger btn-sm" type="button" style="cursor: pointer;" data-toggle="modal" data-id="<?php echo $row['FacultyID']; ?>" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i> </a>
                      </div>
                      <?php 
                    }
                  ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- End of Table Responsive -->
    <?php echo $pagination->createLinks(); 
  } 
  else { ?>
    <div  align="center">
      <span class="pull-right">
        <strong class="text-success">Total no. of rows: 0</strong>
      </span>
      <br>
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="myTable">
          <thead>
            <tr>
              <th><label class="checkbox-inline"><input type="checkbox" class="select-all form-check-input" /><span class="lbl"></span> </label></th>
              <th>No.</th>
              <th>Dental</th>
              <th>Medical</th>
              <th>Name</th>
              <th>Faculty No.</th>
              <th>Department</th>
              <th>Date Added</th>  
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="13" align="center">
                <p>No records found</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- End of Table Responsive -->
    </div>
    <?php 
  }
} 
else {
  $start = !empty($_POST['page'])?$_POST['page']:0;
  $limit = 5;

  //get number of rows
  $queryNum = $DB_con->query("SELECT COUNT(*) as postNum FROM `faculty_stats` JOIN `faculties` ON `faculties`.`facultyNo`=`faculty_stats`.`facultyNo` JOIN `department` ON `faculties`.`dept`=`department`.`dept_id` WHERE `faculties`.`status` = 'active'");
  $resultNum = $queryNum->fetch_assoc();
  $rowCount = $resultNum['postNum'];

  //initialize pagination class
  $pagConfig = array(
    'totalRows' => $rowCount,
    'perPage' => $limit,
    'link_func' => 'searchFilter'
  );
  $pagination =  new Pagination($pagConfig);

  //get rows
  $query = $DB_con->query("SELECT *, CONCAT(last_name,', ',first_name,' ',middle_name,' ',ext) AS full_name FROM `faculty_stats` JOIN `faculties` ON `faculties`.`facultyNo`=`faculty_stats`.`facultyNo` JOIN `department` ON `faculties`.`dept`=`department`.`dept_id` WHERE `faculties`.`status` = 'active' ORDER BY date_updated DESC LIMIT $limit");

  if($query->num_rows > 0){ ?>
    <span class="pull-right"><strong class="text-success">Total no. of rows: <?php echo $rowCount;?></strong></span>
    <br>
    <div class="table-responsive">
      <table class="table  table-striped table-bordered" id="myTable">
        <thead>
          <tr>
            <th><label class="checkbox-inline"><input type="checkbox" class="select-all form-check-input" /><span class="lbl"></span></th>
            <th>No.</th>
            <th>Dental</th>
            <th>Medical</th>
            <th>Name</th>
            <th>Faculty No.</th>
            <th>Department</th>     
            <th>Date Added</th>      
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          while($row = $query->fetch_assoc()){ 
          $deleted = "deleted";
          $start++; ?>
          <tr id="table-row-<?php echo $row["StatsID"]; ?>">
            <td>
              <label class="checkbox-inline"><input type="checkbox" name="chk[]" id="check" class="chk-box form-check-input" value="<?php echo $row['FacultyID']; ?>"  /> <span class="lbl"></span></label>
            </td>
            <td><?php echo $start;?></td>
            <td><?php echo $row['dent']; ?></td>
            <td><?php echo $row['med']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['facultyNo']; ?></td>
            <td><?php echo $row['dept_name']; ?></td>
            <td><?php echo date('F j, Y', strtotime($row['date_registered']));?></td>
            <td>
              <div class="btn-toolbar action" role="toolbar">
                <a href="profile.php?FacultyID=<?php echo $row['FacultyID']; ?>" class="btn btn-sm btn-warning" title="Profile" data-toggle="tooltip" data-placement="top"> <i class="glyphicon glyphicon-user"></i></a><a class="btn btn-sm btn-primary" title="Edit" data-toggle="modal" data-target="#view-modal" data-id="<?php echo $row['FacultyID']; ?>" id="getUser"> <i class="fa fa-pencil"></i></a><a class="btn btn-danger btn-sm" type="button" style="cursor: pointer;" data-toggle="modal" data-id="<?php echo $row['FacultyID']; ?>" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i> </a>
              </div>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <!-- End of Table Responsive -->
    <?php echo $pagination->createLinks(); 
    } else { ?>
      <div  align="center">
      <span class="pull-right">
        <strong class="text-success">Total no. of rows: 0</strong>
      </span>
      <br>
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="myTable">
          <thead>
            <tr>
              <th><label class="checkbox-inline"><input type="checkbox" class="select-all form-check-input" /><span class="lbl"></span> </label></th>
              <th>No.</th>
              <th>Dental</th>
              <th>Medical</th>
              <th>Name</th>
              <th>Faculty No.</th>
              <th>Department</th>
              <th>Date Added</th>     
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="13" align="center">
                <p>No records found</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- End of Table Responsive -->
    </div> 
      <?php
    }
  } 
?>

<script type="text/javascript">
//  for select / deselect all
$(document).ready(function() {

  $("[data-toggle=tooltip]").tooltip();
  $("[data-toggle=modal]").tooltip();

  $(".select-all").change(function () {
    $(".chk-box").prop('checked', $(this).prop("checked"));
    $("#uncheck-all").toggle();
    $("#check-all").toggle();
  });    

  $(".chk-box").click(function() {
    if($(".chk-box").length == $(".chk-box:checked").length) {
      $(".select-all").attr("checked", "checked");
    }
    else {
      $(".select-all").removeAttr("checked");
    }
  });

  //Single SMS
  $("#modal-sms-single").on('show.bs.modal', function (e) {    
    var uid = $(e.relatedTarget).data('id');
    $("#modal-sms-single #smsID").val(uid);
  });

  //Delete Single
  $("#confirm-delete").on('show.bs.modal', function (e) {    
    var uid = $(e.relatedTarget).data('id');
    $("#confirm-delete #delID").val(uid);
  });
});

function delete_records() {
  var id = [];       
  $('input[name="chk[]"]:checked').each(function(i){
    id[i] = $(this).val();
  });
         
  if(id.length === 0) { //tell you if the array is empty
    $("#modal-alert").modal('show');
    return false;
  }
  else {
    $("#modal-confirm").modal('show');
    $("#modal-confirm #modal-btn-yes").click(function () {
      $.ajax({
        url:'delete_mul.php',
        method:'POST',
        data:{id:id},
        success:function() {
          for(var i=0; i<id.length; i++) {
            $('tr#table-row-'+id[i]+'').css('background-color', '#ddd');
            $('tr#table-row-'+id[i]+'').fadeOut('slow');
          }
          $("#modal-confirm").modal('hide');
          $("#tbl_faculties").load("../faculties/tbl_faculties.php");
          $.bootstrapGrowl(id.length + " rows deleted successfully", // Messages
            { // options
              type: "success", // info, success, warning and danger
              ele: "body", // parent container
              offset: {
                from: "top",
                amount: 20
              },
              align: "right", // right, left or center
              width: 300,
              delay: 4000,
              allow_dismiss: true, // add a close button to the message
              stackup_spacing: 10
          });
        }
      });
    });
    $("#modal-confirm #modal-btn-no").click(function () {
      $(".select-all").removeAttr("checked");
      $(".chk-box").prop('checked', false);
    });
  }  
}

//Send SMS
function send_sms() {
  var id = [];       
  $('input[name="chk[]"]:checked').each(function(i){
    id[i] = $(this).val();
  });
         
  if(id.length === 0) { //tell you if the array is empty
    $("#modal-alert").modal('show');
    return false;
  }
  else {
    $("#modal-sms").modal('show');
    $("#modal-sms #modal-btn-send").click(function () {
      message = $("#message-text").val();
      sender = $("#sender-name").val();
      $.ajax({
        url:'send_sms.php',
        method:'POST',
        data:{id:id,message:message,sender:sender},
        beforeSend: function () {
          $("#modal-sms #modal-btn-send").html("<span class='fa fa-envelope'></span>  Sending message");  
        },
        success : function(response) {           
          if(response=="ok"){
            $.bootstrapGrowl("<span class='fa fa-check'></span> Message sent!", // Messages
              { // options
                type: "success", // info, success, warning and danger
                ele: "body", // parent container
                offset: {
                  from: "top",
                  amount: 20
                },
                align: "right", // right, left or center
                width: 300,
                allow_dismiss: true, // add a close button to the message
                stackup_spacing: 10
              }
            );
          }
          else {
            $.bootstrapGrowl("<i class='fa fa-info'></i> "+response, { // Messages
              // options
              type: "danger", // info, success, warning and danger
              ele: "body", // parent container
              offset: {
                from: "top",
                amount: 20
              },
              align: "right", // right, left or center
              width: 300,
              allow_dismiss: true, // add a close button to the message
              stackup_spacing: 10
            });
          }
          $("#modal-sms").modal('hide');
          $(".select-all").removeAttr("checked");
          $(".chk-box").prop('checked', false);
        }
      });
    });
    $("#modal-sms #modal-btn-cancel").click(function () {
      $(".select-all").removeAttr("checked");
      $(".chk-box").prop('checked', false);
    });
  }  
}

$('#close').click(function() {
  window.location.href = 'index.php';
  return false;
});
</script>

<!-- Quick Edit -->
<script>
$(document).ready(function() {
  $('td:contains("Pending")').css('color', 'red');
  $('td:contains("Ok")').css('color', 'green');
  if ($('tr:contains("Restore")')) {
    $('tr:contains("Restore")').css('background-color', 'lightgoldenrodyellow');
    $('tr:contains("Restore") td').css('border-color', 'palegoldenrod');
  }
});

function editRow(editableObj) {
  $(editableObj).css("background","#FFF");
}

function saveToDatabase(editableObj,column,id) {
  $(editableObj).css("background","#FFF url(../images/loading.gif) no-repeat right");
  $.ajax({
    url: "quick_edit.php",
    type: "POST",
    data:'med='+column+'&dent='+column+'&last_name='+column+'&editval='+$(editableObj).text()+'&StatsID='+id,
    success: function(data){
      $(editableObj).css("background","#FDFDFD");

      $('#overlay').show();
      $('#overlay').fadeOut('fast');

      $('td:contains("Pending")').css('color', 'red');
      $('td:contains("Ok")').css('color', 'green');

    }
  });
}
</script>
<?php
//Include database configuration file
include('../../includes/dbconnect.php');
include '../../includes/date_time_diff.php';
$DB_con = new mysqli("localhost", "root", "", "records");
//Include pagination class file
include('../../includes/Pagination.php');

if(isset($_POST['page'])){
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 5;
    
    //set conditions for search
    $whereSQL = $orderSQL = '';
    $keywords = $_POST['keywords'];
    $sortBy = $_POST['sortBy'];
    $prog = $_POST["program_id"];
    $archive = $_POST["archive"];
    $count = $_POST['num_rows'];

    //For number of rows per page
    if ( $count ){
      $limit = $_POST['num_rows']; 
    }

    if ( !empty($keywords) && !empty($prog) ) {
      $whereSQL = " WHERE program = '".$prog."' AND `students`.`studentNo` LIKE '%".$keywords."%' ";
    }
    elseif ( !empty($keywords) ) {
      $whereSQL = " WHERE `students`.`studentNo` LIKE '%".$keywords."%' ";
    }
    elseif ( !empty($prog) ) {
      $whereSQL = " WHERE program = '".$prog."' ";
    }

    //For showing/hiding deleted rows   
    if ( !empty($archive) ) {
      $whereSQL .= " AND CONCAT(`students_den`.`status` = '".$archive."' OR `students_den`.`status` = '".$archive."') ";
    }
    elseif ( empty($archive) ) {
      $whereSQL .= " AND CONCAT(`students_den`.`status` = 'active' OR `students_den`.`status` = 'deleted') ";
    }

    if ( !empty($sortBy) ){
      $whereSQL .= " ORDER BY last_name ".$sortBy;
    }
    elseif ( !empty($sortBy) && !empty($archive) ){
      $whereSQL .= " ORDER BY date_deleted ".$sortBy;
    } 
    elseif ( !empty($sortBy) && !empty($prog) ) {
      $whereSQL .= " ORDER BY last_name ".$sortBy;
    }
    elseif (empty($prog) || empty($sortBy)) {
      $orderSQL .= " ORDER BY date_updated DESC ";
    }

    //get number of rows
    $queryNum = $DB_con->query("SELECT COUNT(*) as postNum FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `students_den` ON `students`.`StudentID`=`students_den`.`StudentID` JOIN `program` ON `students`.`program`=`program`.`program_id` $whereSQL $orderSQL");
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
    $query = $DB_con->query("SELECT * FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `students_den` ON `students`.`StudentID`=`students_den`.`StudentID` JOIN `program` ON `students`.`program`=`program`.`program_id` $whereSQL $orderSQL LIMIT $start,$limit");
    
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
                <th>
                <label class="checkbox-inline"><input type="checkbox" class="select-all form-check-input" /><span class="lbl"></span> </label></th>
                <th>No.</th>      
                <th width="100px">Student No.</th>
                <th colspan="2">Diagnosis</th>
                <th>School Nurse</th>   
                <th>Date of Checkup</th>         
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
              while($row = $query->fetch_assoc()){ 
              $start++; ?>
              <tr id="table-row-<?php echo $row["StatsID"]; ?>">
                <td>
                  <label class="checkbox-inline"><input type="checkbox" name="chk[]" id="check" class="chk-box form-check-input" value="<?php echo $row['StudentID']; ?>"  /> <span class="lbl"></span></label>
                </td>
                <td><?php echo $start;?></td>
                <td><?php echo $row['studentNo']; ?></td>
                <td><?php echo $row['per_con'];?></td>
                <td><?php echo $row['con_rem1']."".$row['con_rem2']."".$row['con_rem3']."".$row['con_rem4'];?></td>
                <td><?php echo $row['checked_by'];?></td>
                <td><?php echo date('F j, Y; h:i a', strtotime($row['date_checked']));?></td>
                <td style="width: 145px;">
                  <div class="btn-toolbar action" role="toolbar">
                  <?php 
                    if ($row['status'] == 'deleted') { 
                      ?>
                      <button type="button" class="btn btn-success" id="restore" value="<?php echo $row['DID']; ?>"><i class="fa fa-undo"></i> Restore</button>
                      <?php 
                    }
                    else { 
                      ?>
                      <a href="/LUMDRMS/dental/students/dental.php?DID=<?php echo $row['DID']; ?>" class="btn btn-sm btn-warning" title="View Dental" data-toggle="tooltip" data-placement="bottom"> <i class="fa fa-external-link" aria-hidden="true"></i></a> <button class="btn btn-sm btn-danger delete" title="Delete" data-toggle="tooltip" data-placement="bottom" value="<?php echo $row['DID']; ?>"><span class = "glyphicon glyphicon-trash"></span></button>
                      <?php 
                      }
                    ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          </div>
          <!-- End of Table Responsive -->
        </form>
      </div>
      <!-- End of Container Fluid -->
    </div>
    <!-- End of Table -->
    <?php echo $pagination->createLinks(); ?>
  <?php } 
  else { 
    echo "<div class='alert alert-warning'>No result</div>"; 
  }
} 
else {
  $start = !empty($_POST['page'])?$_POST['page']:0;
  $limit = 5;

  //get number of rows
  $queryNum = $DB_con->query("SELECT COUNT(*) as postNum FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `students_den` ON `students`.`StudentID`=`students_den`.`StudentID` JOIN `program` ON `students`.`program`=`program`.`program_id` WHERE `students_den`.`status` = 'active'");
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
  $query = $DB_con->query("SELECT * FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `students_den` ON `students`.`StudentID`=`students_den`.`StudentID` JOIN `program` ON `students`.`program`=`program`.`program_id` WHERE `students_den`.`status` = 'active' ORDER BY date_updated DESC LIMIT $limit");

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
                <th>
                <label class="checkbox-inline"><input type="checkbox" class="select-all form-check-input" /><span class="lbl"></span> </label></th>
                <th>No.</th>      
                <th width="100px">Student No.</th>
                <th colspan="2">Diagnosis</th>
                <th>School Nurse</th>   
                <th>Date of Checkup</th>         
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
              while($row = $query->fetch_assoc()){ 
              $start++; ?>
              <tr id="table-row-<?php echo $row["StatsID"]; ?>">
                <td>
                  <label class="checkbox-inline"><input type="checkbox" name="chk[]" id="check" class="chk-box form-check-input" value="<?php echo $row['StudentID']; ?>"  /> <span class="lbl"></span></label>
                </td>
                <td><?php echo $start;?></td>
                <td><?php echo $row['studentNo']; ?></td>
                <td><?php echo $row['per_con'];?></td>
                <td><?php echo $row['con_rem1']."".$row['con_rem2']."".$row['con_rem3']."".$row['con_rem4'];?></td>
                <td><?php echo $row['checked_by'];?></td>
                <td><?php echo date('F j, Y; h:i a', strtotime($row['date_checked']));?></td>
                <td style="width: 145px;">
                  <div class="btn-toolbar action" role="toolbar">
                  <?php 
                    if ($row['status'] == 'deleted') { 
                      ?>
                      <button type="button" class="btn btn-success" id="restore" value="<?php echo $row['DID']; ?>"><i class="fa fa-undo"></i> Restore</button>
                      <?php 
                    }
                    else { 
                      ?>  
                      <a href="/LUMDRMS/dental/students/dental.php?DID=<?php echo $row['DID']; ?>" class="btn btn-sm btn-warning" title="View Dental" data-toggle="tooltip" data-placement="bottom"> <i class="fa fa-external-link" aria-hidden="true"></i></a> <button class="btn btn-sm btn-danger delete" title="Delete" data-toggle="tooltip" data-placement="bottom" value="<?php echo $row['DID']; ?>"><span class = "glyphicon glyphicon-trash"></span></button>
                      <?php 
                      }
                    ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <!-- End of Table Responsive -->
      </form>
    </div>
    <!-- End of Container Fluid -->
  </div>
  <!-- End of Table -->
  <?php echo $pagination->createLinks(); 
  } else { 
    echo "<div class='alert alert-warning'>No result</div>"; 
  }
}
?>

<script type="text/javascript">
$(document).ready(function() {

  $("[data-toggle=tooltip]").tooltip();

  //  for select / deselect all
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
});

//  for select / deselect all
function delete_records() {
  var id = [];       
  $('input[name="chk[]"]:checked').each(function(i){
    id[i] = $(this).val();
  });
         
  if(id.length === 0) { //tell you if the array is empty
    alert("Please Select atleast one checkbox");
    return false;
  }
  else {
    confirm("Are you sure you want to delete this?");
    $.ajax({
      url:'delete_mul.php',
      type:'POST',
      data:{id:id},
      success:function() {
        for(var i=0; i<id.length; i++) {
          $('tr#table-row-'+id[i]+'').css('background-color', '#ddd');
          $('tr#table-row-'+id[i]+'').fadeOut('slow');
        }
        $("#tbl_students").load("tbl_students.php");
        $.bootstrapGrowl("Deleted successfully", // Messages
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
  }  
}
$('#close').click(function() {
  window.location.href = 'index.php';
  return false;
});

// Quick Edit 
$('document').ready(function() {
  $('td:contains("Pending")').css('color', 'red');
  $('td:contains("Ok")').css('color', 'green');
});

function editRow(editableObj) {
  $(editableObj).css("background","#FFF");
}

function saveToDatabase(editableObj,column,id) {
  $(editableObj).css("background","#FFF url(../../images/loading.gif) no-repeat right");
  $.ajax({
    url: "../../students/quick_edit.php",
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
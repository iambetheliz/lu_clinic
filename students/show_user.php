<?php
	require_once '../includes/dbconnect.php';
    $DB_con = new mysqli("localhost", "root", "", "records");

    if(isset($_POST['page'])) {
        $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
        if(!is_numeric($page_number)){
            die('Invalid page number!');
            } //incase of invalid page number
        }
    else {
        $page_number = 1;
    }

    //get current starting point of records
    $position = (($page_number-1) * $item_per_page);

    $result = mysqli_query($DB_con,"SELECT COUNT(*) as postNum FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `program` ON `students`.`program`=`program`.`program_id`"); 
    $rowCount = $result->fetch_assoc();
    $count = $rowCount['postNum'];
    $result = mysqli_query($DB_con,"SELECT * FROM `students_stats` JOIN `students` ON `students`.`studentNo`=`students_stats`.`studentNo` JOIN `program` ON `students`.`program`=`program`.`program_id` $where ORDER BY last_name ASC LIMIT $position, $item_per_page"); 
?>
	<div class="row">
        <div class="container-fluid">
            <form method="post" name="frm">
                <label class="checkbox-inline"><input type="checkbox" class="select-all" /> <strong>Check / Uncheck All</strong></label>
                <span style="word-spacing:normal;"> | With selected :</span>
                <label id="actions">
                  <span><a class="text-danger" style="cursor: pointer;" onClick="delete_records();" title="Click to delete selected rows" data-toggle="tooltip"> Delete</a>
                </label>
                <label class="pull-right">Total number of rows: <?php echo $count; ?></label>
                <br>
                <div class="table-responsive">
                <?php        
                if ($result->num_rows != 0) { ?>
                    <table class="table  table-striped table-bordered sortable" id="myTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 85px;">Medical</th>
                                <th style="width: 85px;">Dental</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th style="width: 120px;">Student No.</th>
                                <th>Program</th>
                                <th style="width: 60px;">Year</th>            
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
					<?php
						while($row = $result->fetch_assoc()){
                            $postID = $row['StudentID'];
								if (($row['med']) != 'Ok') {
                                    $color = "red";
                                    $status = "Ok";
                                }
                                else {
                                    $color = "green";
                                }
                                if (($row['dent']) != 'Pending') {
                                    $color2 = "green";
                                }
                                else {
                                    $color2 = "red";
                                }
                                if (!empty($row['ext'])) {
                                    $extension = ", ";
                                }
                                else {
                                    $extension = " ";
                                }
							?>
								<tr data-row-id="<?php echo $row['StudentID'];?>">
                                <td><input type="checkbox" name="chk[]" class="chk-box" value="<?php echo $row['StudentID']; ?>"  /></td>
                                <td style="color:<?php echo $color;?>;" class="editable-col" contenteditable="true" col-index='0' oldVal ="<?php echo $row['med'];?>">
                                    <?php echo $row['med']; ?> 
                                </td>
                                <td style="color:<?php echo $color2;?>;">
                                    <?php echo $row['dent']; ?>
                                </td>
                                <td><?php echo strtoupper($row['last_name']); ?></td>
                                <td><?php echo strtoupper($row['first_name']);echo $extension.strtoupper($row['ext']); ?></td>
                                <td><?php echo strtoupper($row['middle_name']); ?></td>
                                <td><?php echo $row['studentNo']; ?></td>
                                <td><?php echo $row['program_name'];?></td>
                                <td><?php echo $row['yearLevel'];?></td>
								<td style="width: 145px;"><a href="profile.php?StudentID=<?php echo $row['StudentID']; ?>" class="btn btn-sm btn-warning" title="View" data-toggle="tooltip"> <i class="fa fa-external-link" aria-hidden="true"></i></a> |  <a href="edit_student.php?StudentID=<?php echo $row['StudentID']; ?>" class="btn btn-sm btn-primary" title="Edit" data-toggle="tooltip"> <i class="fa fa-pencil"></i></a> | <button class="btn btn-sm btn-danger delete" title="Delete" data-toggle="tooltip" value="<?php echo $row['StudentID']; ?>"><span class = "glyphicon glyphicon-trash"></span></button>
								</td>
							</tr>
                        <?php }
                            } 
                        else {
                                $errMSG = "No records found."; 
                        }?>
                    </tbody>
                </table>
                <?php 
                        if(isset($errMSG)){ ?>

                        <div class="alert alert-warning">
                            <span class="glyphicon glyphicon-info"></span> 
                            <?php echo $errMSG; ?>
                        </div>
                </div>
                <!-- End of Table Responsive -->
            </form>
        </div>
        <!-- End of Container Fluid -->
    </div>
    <!-- End of Table -->
<?php
	
}
?>
<script type="text/javascript">
    //  for select / deselect all
    $('document').ready(function() {
        $("[data-toggle=tooltip]").tooltip();
        $(".select-all").change(function () {
            $(".chk-box").prop('checked', $(this).prop("checked"));
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
        document.frm.action = "delete_mul.php";
        document.frm.submit();
    }
    $('#close').click(function() {
        window.location.href = 'index.php';
        return false;
    });
    </script>
<script src="../assets/js/sorttable.js"></script>
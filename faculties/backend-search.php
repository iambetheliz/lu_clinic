<?php
require_once("../includes/dbconnect.php");
$DB_con = new mysqli("localhost", "root", "", "records");

if(!empty($_POST["keyword"])) {
    $query ="SELECT * FROM faculties WHERE facultyNo LIKE '%" . $_POST["keyword"] . "%' OR first_name LIKE '%" . $_POST["keyword"] . "%' OR middle_name LIKE '%" . $_POST["keyword"] . "%' OR last_name LIKE '%" . $_POST["keyword"] . "%' ORDER BY first_name LIMIT 0,6";
    $result = $DB_con->query($query);
    if(!empty($result)) {
?>
        <ul id="country-list">
        <?php
            foreach($result as $row) {
        ?>
            <li onClick="selectCountry('<?php echo $row["first_name"]." ".$row['middle_name']." ".$row['last_name']; ?>');"><a href="profile.php?FacultyID=<?php echo $row['FacultyID'];?>"><?php echo $row["first_name"]." ".$row['middle_name']." ".$row['last_name']; ?></a></li>
        <?php 
            } 
        ?>
        </ul>
<?php 
    } 
} 
?>
<?php
$con = new PDO("mysql:host=localhost;dbname=face_project",'root','');

$filterDate = $_REQUEST['filterDate'];
if(!empty($filterDate)){
    $date = date('Y-m-d',strtotime($filterDate));
}else{
    $date = date('Y-m-d');
}
$query2 = "SELECT * FROM `tbl_students_attendance` WHERE  date = '".$date."' ";                    
$sth2 = $con->prepare($query2);
$sth2->execute();

$result=$sth2->fetchAll(); 
?>

<table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Student Register Number</th>
          <th>Date</th>
          <th>Attendance</th>
        </tr>
      </thead>
      <tbody id="attendanceTable">
        <!-- Example records -->

        <?php for($i=0;$i<count($result);$i++){?>
          <tr data-date="<?php echo $result[$i]['date']?>">
            <td><?php echo $result[$i]['student_reg_number']?></td>
            <td><?php echo $result[$i]['date']?></td>
            <td><?php echo $result[$i]['is_present']=='1'?'Present':'Absent' ?></td>
          </tr>
        <?php }?>
        
      </tbody>
    </table>
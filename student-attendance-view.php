<?php
// $con = new PDO("mysql:host=localhost;dbname=face_project",'root','');

$date = date('Y-m-d');

// $query2 = "SELECT * FROM `tbl_students_attendance` WHERE  date = '".$date."' ";                    
// $sth2 = $con->prepare($query2);
// $sth2->execute();

// $result=$sth2->fetchAll(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Attendance Records</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
    }

    .overlay {
      min-height: 100vh;
      background-color: rgba(255, 255, 255, 0.95);
      padding: 40px;
    }

    .table-container {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      padding: 20px;
    }

    .form-label {
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="overlay">
  <div class="container table-container">
    <h3 class="text-center mb-4">Student Attendance Records</h3>
    <form id="searchList">
    <div class="row mb-3">
      <div class="col-md-4">
      
        <input type="date" id="filterDate" name="filterDate" class="form-control" value="<?php echo $date;?>">
        
      </div>
      <div class="col-md-4">
      <label for="filterDate" class="form-label"></label>
        <input type="button" class="btn btn-primary" onClick="load_data(1)" value="Go" style="height: 40px !important;">
      </div>
    </div>
    </form>
    <div id="container"></div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  function load_data(){
    var filterDate=$('#searchList').find('input[name="filterDate"]').val();
    $.ajax({
			url:"studenet_attendance_fetch.php",
			method:"POST",data:"filterDate="+filterDate,//+"&cat_id="+cat_id,
			success:function(data){
				$('#container').html(data);
			}
		})

  }
const filterDate = document.getElementById('filterDate');
  const tableRows = document.querySelectorAll('#attendanceTable tr');

 filterDate.addEventListener('input', () => {
  const selectedDate = filterDate.value;

tableRows.forEach(row => {
  const rowDate = row.getAttribute('data-date');
  if (!selectedDate || rowDate === selectedDate) {
    row.style.display = '';
  } else {
    row.style.display = 'none';
  }
});
});


load_data();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

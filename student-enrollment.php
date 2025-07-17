<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>College Student Enrollment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('asset/sathiyabama-college.png') no-repeat center center fixed;
      background-size: cover;
    }

    .overlay {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      max-width: 500px;
      width: 100%;
    }

    video, canvas, #capturedImage {
      width: 100%;
      border-radius: 10px;
      margin-top: 10px;
    }

    #capturedImage {
      display: none;
    }

    .form-label {
      font-weight: 600;
    }
  </style>
</head>
<body>

<div class="overlay">
  <div class="card">
    <h3 class="text-center mb-4">Student Enrollment</h3>

    <?php if (isset($_REQUEST['msg'])) {
    switch ($_REQUEST['msg']) {
    case '0':
        $loginstatus = "Error In Add Face To collection";
        break;
          }
          echo '<div class="alert alert-danger"  style="color:#a94442;padding:0 0 0 219px;" id="status_msg">'.$loginstatus.'</div>';
      } 
    ?>
    <!--<form id="enrollForm">-->
      <div class="mb-3">
        <label for="student_reg_number" class="form-label">Student Register Number</label>
        <input type="text" class="form-control" id="student_reg_number" name="student_reg_number" placeholder="Enter Student Register Number" required>
      </div>

      <div class="mb-3" id="after_snapshot">
        <label class="form-label">Live Photo</label>
        <video id="video" autoplay playsinline></video>
        <button type="button" class="btn btn-secondary btn-sm mt-2" id="captureBtn">Capture Photo</button>
        <canvas id="canvas" style="display: none;"></canvas>
        <img id="capturedImage" alt="Captured Image" />
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-4" onclick="return ajaxfunction()">Submit Enrollment</button>
      </div>
    <!--</form>-->
    <div id="resultsv1"></div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  const video = document.getElementById('video');
  const captureBtn = document.getElementById('captureBtn');
  const canvas = document.getElementById('canvas');
  const capturedImage = document.getElementById('capturedImage');

  // Enable webcam
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => {
      alert("Unable to access the camera. Please check permissions.");
      console.error(err);
    });

  // Capture photo
  captureBtn.addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const imageData = canvas.toDataURL('image/jpeg');
    capturedImage.src = imageData;
    capturedImage.style.display = 'block';
  });

</script>
<script language="JavaScript">

	
 
		 function ajaxfunction()
        {
            var uniqueId = document.getElementById('student_reg_number').value;
          
            $.ajax({
                        url: "checkStudentRegister.php",
    					data: 'reg_no=' + uniqueId,  
                        type: "POST",
                        success: function (data) {
    
                            if(data == 2){
                                alert('This Student Register Number  Already exists');
                                $('#student_reg_number').val('');
                                return false;
                            }else {
                                    var someimage = document.getElementById('after_snapshot');
                                    var myimg = someimage.getElementsByTagName('img')[0];
                                    var mysrc = myimg.src;
                                    
                                    var names = '';//document.getElementById('names').value;
                                    var student_reg_number = document.getElementById('student_reg_number').value;
                                    if(student_reg_number==""){
                                    alert("Student register number is empty, Click close and add again");return false;
                                    }
                                    //	console.log(mysrc);
                                    
                                    $.ajax({
                                    url: "uploadv1.php",
                                    data: 'image_src=' + mysrc+'&student_reg_number=' +student_reg_number,  
                                    type: "POST",
                                    success: function (data) {
                                    
                                    if(data == 1){
                                     alert('Image Captured Successfully');
                                     window.parent.$('.close').click();
                                     location.reload();
                                    
                                    }else{
                                     alert(data);  
                                     location.reload();
                                    
                                    }
                                    }
                                    });
                            }
    					 
                        }
                    });
        }
	
	</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

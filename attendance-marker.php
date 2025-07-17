<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Attendance Capture</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('asset/marker-1.jpg') no-repeat center center fixed;
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
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
    <h3 class="text-center mb-4">Student Attendance</h3>
    <!-- <form id="attendanceForm"> -->
      <div class="mb-3">
        <label for="attendanceDate" class="form-label">Select Date</label>
        <input type="date" class="form-control" id="attendanceDate"  name="attendanceDate" required>
      </div>

      <div class="mb-3"  id="after_snapshot">
        <label class="form-label">Capture Your Photo</label>
        <video id="video" autoplay playsinline></video>
        <button type="button" class="btn btn-sm btn-secondary mt-2" id="captureBtn">Capture Image</button>
        <canvas id="canvas" style="display: none;"></canvas>
        <img id="capturedImage" alt="Captured Image" />
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4" onclick="return ajaxfunction()">Submit Attendance</button>
      </div>
    <!-- </form> -->
    <div id="resultsv1"></div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
  const video = document.getElementById('video');
  const captureBtn = document.getElementById('captureBtn');
  const canvas = document.getElementById('canvas');
  const capturedImage = document.getElementById('capturedImage');

  // Enable camera
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => {
      alert("Camera access denied or unavailable.");
      console.error(err);
    });

  // Capture snapshot
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
      var someimage = document.getElementById('after_snapshot');
      var myimg = someimage.getElementsByTagName('img')[0];
      var mysrc = myimg.src;
      
      var names = '';//document.getElementById('names').value;
      var attendanceDate = document.getElementById('attendanceDate').value;
      if(attendanceDate==""){
      alert("Attendance Date is empty, Click close and add again");return false;
      }
    
      
      $.ajax({
        url: "capture-attendence.php",
        data: 'image_src=' + mysrc+'&attendanceDate=' +attendanceDate,  
        type: "POST",
        success: function (data) {
        
          if(data == 1){
            alert('Attendance Captured Successfully');
            window.parent.$('.close').click();
          
          
          }else{
            alert(data);  
            console.log(data);
            //location.reload();
          
          }
        }
      });
            
    }
	
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

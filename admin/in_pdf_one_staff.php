<html>
  <head>
    <script type = "text/javascript">
      function openURL_working() {
		 var staffid = document.getElementById('txt_staffid').value;
		 var month = document.getElementById('month').value;
		 var strurl = 'in_pdf.php?staffid=' + staffid + '&month=' + month;
		 window.open(strurl); 
	      }
    </script> 
  </head>
<body>
  <?php
      session_start();
      $manhanvien = $_SESSION['id'];
      if($manhanvien == 0){
	    header("location:index.php");
      }
  ?>
  <h3>Enter staff id to print attendace record</h3>
    <lable>Staffid</lable>
    <input type="text" id="txt_staffid"/>
    <label>Choose a month : </label>
    <select id ="month">
	<option value = "1">1</option>
	<option value = "2">2</option>
	<option value = "3">3</option>
	<option value = "4">4</option>
	<option value = "5">5</option>
	<option value = "6">6</option>
	<option value = "7">7</option>
	<option value = "8">8</option>
	<option value = "9">9</option>
	<option value = "10">10</option>
	<option value = "11">11</option>
	<option value = "12">12</option>
    </select>
  <button onclick='openURL_working();'>Print view</button>
</body>
</html>
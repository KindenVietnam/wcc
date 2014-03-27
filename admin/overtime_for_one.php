<html>
  <head>
    <script type = "text/javascript">
      function openURL_add(sURL) { 
		 window.open(sURL,"Window1","menubar=no,width=600,height=500,toolbar=no"); 
	      }
      function openURL_working(sURL) {
		 var staffid = document.getElementById('txt_staffid').value;
		 var strurl = 'search_att.php?id=' + staffid;
		 window.open(strurl,"Window1","menubar=no,width=auto,height=auto,toolbar=no,scrollbars=yes"); 
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
  <h3>Enter staff id to process overtime</h3>
    <lable>Staffid</lable>
    <input type="text" id="txt_staffid"/>
  <button onclick='openURL_working();'>Process</button>
</body>
</html>
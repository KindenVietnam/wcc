<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type = "text/javascript">
  function openURL_save_db_temp(sURL){ 
             window.open(sURL,"Window2","menubar=no,width=100,height=100,toolbar=no"); 
          }
  function openURL_working(sURL){ 
             //window.open(sURL,"Window1","width=1200,height=700"); 
			 window.open(sURL); 
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
<form name="form1" method="post" action="welcome.php?6">
<table>
    <tr>
        <td>
            <h2>Print attendance record for all staffs</h2>
            <!--<lable>Choose a month
              <select name="month">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
              <option>6</option>
              <option>7</option>
              <option>8</option>
              <option>9</option>
              <option>10</option>
              <option>11</option>
              <option>12</option>
              </select>
            </lable>-->
            <input type="submit" name="print" value="Print view">
        </td>
        <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </td>
        <td>
            <!--<h2>Submit Attendance Record</h2>
            <input type="submit" name="savetotemp" value="Submit Attendance record">-->
        </td>
    </tr>
</table>
</form>

<?php
    $status = $_POST['radiobutton'];
    $manhanvien = $_POST['txtstaffid'];
    $month = $_POST['month'];
    if(isset($_POST['print'])){
        echo "<script>openURL_working('att_pdf/att.pdf');</script>";
		//header("location:welcome.php?7&thang=$month");
    }
    if(isset($_POST['savetotemp'])){
		echo "<script>openURL_save_db_temp('loop_for_save_database.php');</script>";
    }
?>
</body>
<link rel="stylesheet" type="text/css" href="table.css"/>
<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<script src="checkall_js.js" language="javascript"></script>
<script src="insert_in_out.js" language="javascript"></script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Over Time for group</title>
</head>
<body>
<?php
//ket noi database
							session_start();
							$manhanvien = $_SESSION['id'];
							if($manhanvien == 0){
								header("location:index.php");
							}
include('config.php');
if(isset($_POST['approved']))
{
	  $date = $_POST['date'];
	  $time_in = $date.' '.$_POST['time_in'];
	  $time_out = $date.' '.$_POST['time_out'];
	  $status = $_POST['check'];
    foreach($_POST['checklist'] as $check_id){
      if(!empty($_POST['checklist'])){
        if(strcmp(trim($status),'in')==0){
			 $sql_insert_inout = "insert into inout(staff_id,machine_no,checktime) values('$check_id','2','$time_in')";
			 pg_query($connection,$sql_insert_inout);
        }
        elseif(strcmp(trim($status),'out')==0){
              $sql_insert_inout = "insert into inout(staff_id,machine_no,checktime) values('$check_id','2','$time_out')";
              pg_query($connection,$sql_insert_inout);
        }
        elseif(strcmp(trim($status),'in_out')==0){
              $sql_insert_inout = "insert into inout(staff_id,machine_no,checktime) values('$check_id','2','$time_out'),('$check_id','2','$time_in')";
              pg_query($connection,$sql_insert_inout);
        }
        else{
			echo '<h4 class="alert_info">Please choose type of insert time !.</h4>';
        }
      }
    }
}
?>
<h3>IN-OUT For Group</h3>
<form id="form1" name="form_insert_in_out" method="post" action="welcome.php?5">
  <label>Location
  <select name="select_location">
    <?php
            $sql_load_location = "select * from machine where status <> '0'";
            $result_load_location = pg_query($connection, $sql_load_location);
            echo "<option>Select location ... </option>";
            while($row_load_location = pg_fetch_array($result_load_location))
            {
              echo "<option>".$row_load_location['name']."</option>";
            }
    ?>
  </select>
  </label>
  <label>Date
  <input type="text" name="date" onFocus="JavaScript:showCalendarControl(this);"/>
  </label>
  <label>
  <input type="submit" name="Search" value="Search" />
  </label></br></br>
  <input type="radio" name="check" onclick="in_out(1)" value="in"/>Check In</br>
  <input type="radio" name="check" onclick="in_out(2)" value="out"/>Check Out</br>
  <input type="radio" name="check" onclick="in_out(3)" value="in_out"/>Check In and Out</br></br>
  <label>IN</label><input type="text" name="time_in">&nbsp&nbsp&nbsp&nbsp<label>OUT</label><input type="text" name = "time_out">
  </br></br>
  <table width="100%" border="0" id = "tb1">
    <tr>
      <td><b>Number</b></td>
      <td><b>Location</b></td>
      <td><b>Staff ID</b></td>
      <td><b>Name</b></td>
      <td><b>Date</b></td>
      <td><b><a href="Javascript:void();" onclick="JavaScript:checkAll('form_insert_in_out', true);">check all</a> | 
            <a href="Javascript:void();" onclick="JavaScript:checkAll('form_insert_in_out', false);">uncheck all</a></b>
      </td>
    </tr>
	<?php
		if(isset($_POST['Search'])){
		$machine_name = $_POST['select_location'];
		$date = $_POST['date'];
		$sql_load_staff = "select staff.staff_id as staffid, staff.name as staff_name
							from inout,staff,machine
							where inout.staff_id = staff.staff_id and inout.machine_no = machine.machine_no and machine.name = '$machine_name' and date(inout.checktime) = '$date'
							group by staffid,staff_name
							order by staff_name asc";
		$result_load_staff = pg_query($connection,$sql_load_staff);
		$id = 0;
		while($row_load_staff = pg_fetch_array($result_load_staff))
			{
			$id=$id+1;
			echo '<tr>';
			echo '<td>'.$id.'</td>';
			echo '<td>'.$machine_name.'</td>';
			echo '<td>'.$row_load_staff['staffid'].'</td>';
			echo '<td>'.$row_load_staff['staff_name'].'</td>';
			echo '<td><input type="text" name="date" value="'.$date.'"></td>';
			echo '<td><label><input type="checkbox" name="checklist[]" value="'.$row_load_staff['staffid'].'" />In-Out</label></td>';
			echo '</tr>';
			}
		}
	?>
  </table>
  <p>
    <label>
    <input type="submit" name="approved" value="Approve" />
    </label>
    <label>
    <input type="submit" name="cancel" value="Cancel" />
    </label>
  </p>
</form>
<p>&nbsp;</p>

</body>
</html>

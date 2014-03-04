<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<?php
include("config.php");
$id = $_GET['id1'];
$sql_tim = "select * from leaves where id='$id'";
$result = pg_query($connection, $sql_tim);
while($row = pg_fetch_array($result)){
			$staffid=$row['staff_id'];
			$fromdate = $row['fromdate'];
			$todate = $row['todate'];
			$a_p = $row['a_p'];
			$reason = $row['reason'];
			$status = $row['status'];
		}
$sql_name = "select * from staff where staff_id = '$staffid'";
$result1 = pg_query($connection, $sql_name);
while($row1 = pg_fetch_array($result1)){
		$staffname = $row1['name'];
		}
if(isset($_POST['save'])){
	$numberid = $_POST['numberid'];
	$stid = $_POST['staffid'];
	$frdate = $_POST['fromdate'];
	$tdate = $_POST['todate'];
	$reason = $_POST['reason'];
	$status_ = $_POST['status'];
	if($status_ == true)
	{
		$sttus = 'y';
	}
	else{
		$sttus = 'n';
	}
	if (($stid=="")||($frdate=="")){
		echo "please fill information !";
		}
	else{	
		$sql_update = "update leaves set staff_id = '$stid', fromdate = '$frdate',todate = '$tdate',reason='$reason',status = '$sttus' where id = '$numberid'";
		$s = pg_query($connection, $sql_update);
		//header("location:leaves.php?id=$stid");
		echo "Update Successfully !";
	  }
}
echo '<form name="form1" method="post" action="edit_leaves.php">';
echo '<h3>Leave Days';
echo '</h3>';
echo '<table>';
echo "<tr>";
echo "<td>";
echo '<table>';
echo '<tr>'; 
echo '<td>Number ID : </td>';
echo '<td><input type="text" name="numberid" readonly value="'.$id.'"></td>';
echo '</tr>';
echo '<tr>'; 
echo '<td>Staff ID : </td>';
echo '<td><input type="text" name="staffid" readonly value="'.$staffid.'"></td>';
echo '</tr>';
echo '<tr>';
echo "<td>Staff's Name : </td>";
echo '<td><input type="text" name="staffname" readonly value="'.$staffname.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>From : </td>';
echo '<td><input type="text" name="fromdate" onfocus="JavaScript:showCalendarControl(this);" value="'.$fromdate.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>To : </td>';
echo '<td><input type="text" name="todate" onfocus="JavaScript:showCalendarControl(this);" value="'.$todate.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Status : </td>';
if($status == 'y')
{
	$st = 'checked';
}
else
{
	$st = '';
}
echo "<td><input type = 'checkbox' name='status' value='true' ".$st."></td>";
echo '</tr>';
echo '<tr><td>Reason : </td>';
echo '<td><textarea cols="40" rows="5" name="reason">'.$reason.'</textarea></td>';
echo '</tr>';
echo '<tr><td><input type="submit" name="save" value="Save"></td><td></td></tr>';
echo '</table>';
echo '</form>';
?>
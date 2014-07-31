<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<?php
include("config.php");
$staffid = $_GET['id1'];
if(isset($_POST['save'])){
	$staffid = $_POST['staffid'];
	$staffname = $_POST['staffname'];
	$email = $_POST['email'];
	$startday = $_POST['startday'];
        $check_status = $_POST['status'];
        if($check_status == 'checked'){
            $status = 1;
        }
        else{
            $status = 0;
        }
	$sql_update = "update staff set staff_id = '$staffid',name='$staffname',startdate = '$startday',email='$email',status='$status' where staff_id = '$staffid'";
	$s = pg_query($connection, $sql_update);
	header("Location:detail_user.php?id1=$staffid");
}
$sql_loc = "select * from staff where staff_id = '$staffid'";
$result = pg_query($connection, $sql_loc);
while($hang = pg_fetch_array($result)){
	$staffid = $hang['staff_id'];
	$name = $hang['name'];
	$email = $hang['email'];
	$startdate = $hang['startdate'];
	$lastleaves_year = $hang['lastyear_leaves'];
        $status = $hang['status'];
        if($status == 1 ){
            $value_check = 'checked';
        }
        else{
            $value_check = '';
        }
	$year = date("Y") - date("Y", strtotime($startdate));
					if ($year <= 5){
							if($year ==0){
									$leaves_curryear = 12 - date("n", strtotime($startdate));
									}
							else{
									$leaves_curryear = 12;
								}
							}
					elseif((6<=$year) && ($year<=9)){
							$leaves_curryear = 14;
							}
					else {
							$leaves_curryear = 16;
						}
}
if(date("Y-n-j") <= date("Y-06-21")){
	 $phepnamtruoc = $lastleaves_year;
  }
else{
    $phepnamtruoc = 0;
  }
  $totalleaves = $leaves_curryear + $phepnamtruoc;
echo '<form name="form_edit" method="post" action="detail_user.php">';
echo '<table border="0">';
echo '<tr>'; 
echo '<td>Staff ID :</td>';
echo '<td><input type="text" name="staffid" readonly value="'.$staffid.'"></td>';
echo '</tr>';
echo '<tr>'; 
echo '<td>Staff Name :</td>';
echo '<td><input type="text" name="staffname" value="'.$name.'"></td>';  
echo '</tr>';
echo '<tr>'; 
echo '<td>Email :</td>';
echo '<td><input type="text" name="email" value="'.$email.'" size = "40"></td>';  
echo '</tr>';
echo '<tr>'; 
echo '<td>Start Working Day :</td>';
echo '<td><input type="text" name="startday" onfocus="JavaScript:showCalendarControl(this);" value="'.$startdate.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Total Leaves Day in  ' . (date("Y")-1) .'  :  </td>';
echo '<td><input type="text" name="lastyear_leaves" value="'.$phepnamtruoc.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>Total Leaves Day in  ' . date("Y").'  :  </td>';
echo '<td><input type="text" name="totalleavesday" value="'.$totalleaves.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td><input type="checkbox" name="status" '.$value_check.'></td><td></td>';
echo '</tr>';
echo '<tr>';
echo '<td><input type="submit" name="save" value="Save"></td><td></td>';
echo '</tr>';
echo '</table>';
echo '<h3>List of Leaves days in '.date("Y").'</h3>';
echo '<table name="list_leaves" border="0">';
		echo "<tr bgcolor='#C7E2E2'>";
                echo "<td>From </td>";
                echo "<td>To </td>";
		echo "<td>Time</td>";
		echo "<td>Number of leaves day</td>";
		echo "<td>Reason </td>";
                echo "<td>Control </td>";
                echo "</tr>";
                $sql_str = "SELECT * FROM leaves WHERE staff_id='$staffid' AND date_part('year', fromdate) = date_part('year', current_date)";
                $result=pg_query($connection, $sql_str);
                while($res=pg_fetch_array($result)){
							$bien = $res['id'];
							$a_p = $res['a_p'];
							$fromdate = date("j",strtotime($res['fromdate']));
							$todate = date("j",strtotime($res['todate']));
								if (($todate - $fromdate)==0){
										if ($a_p>0){
										$number_leaves = 1/2;
										}
										else{
										$number_leaves = 1;
										}
									}
								else{
										if($a_p>0){
										$number_leaves = $todate - $fromdate - 0.5;
										}
										else{
										$number_leaves = $todate - $fromdate;
										}
									}
							// them phan tinh neu la lon hon thang 6 thi tinh khac
								$subtotalleaves = $subtotalleaves + $number_leaves;
							echo "<tr>";
							echo "<td>".$res['fromdate']."</td>";
							echo "<td>".$res['todate']."</td>";
							echo "<td>".$a_p."</td>";
							echo "<td>".$number_leaves."</td>";
							echo "<td>".$res['reason']."</td>";
echo "<td><a href=\"edit_leaves_sub.php?id1=$bien\">Edit</a> |";
echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='del_leaves_sub.php?id=$bien&staffid=$staffid';}\">Delete</a></td>";
							echo "</tr>";
                       }
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Total leaves day in this month : ';
				echo '</td>';
				echo '<td>'.$subtotalleaves.'</td>';
				echo '</tr>';
				echo '<tr>';
				$left = $totalleaves - $subtotalleaves;
				echo '<td colspan="2">';
				echo 'Left leaves day in this year : ';
				echo '</td>';
				echo '<td>'.$left.'</td>';
				echo '</tr>';
echo '</table>';
echo '</form>';
?>
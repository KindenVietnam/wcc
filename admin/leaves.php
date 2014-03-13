<script type = "text/javascript">
  function openURL_edit(sURL) { 
             window.open(sURL,"Window1","menubar=no,width=500,height=400,toolbar=no"); 
          } 
</script> 
<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<?php
include("config.php");
include_once('leaves_count.php');
$linkback = $_GET['linkback'];
$staffid = $_GET['id'];
$month = $_GET['month'];
$from_date = $_GET['ngay'];
if(isset($_POST['search'])){
	$op = $_POST['type'];
	$staffid = $_POST['txttim'];
		if ($op == "op_id"){
		$sql_tim = "select * from staff where staff_id='$staffid'";
		}
		else{
		$sql_tim = "select * from staff where name ilike '%$staffid%'";
		}
	}
else{
	$sql_tim = "select * from staff where staff_id='$staffid'";
	}
$result = pg_query($connection, $sql_tim);

while($row = pg_fetch_array($result)){
			$staffid = $row['staff_id'];
			$name = $row['name'];
			$startdate = $row['startdate'];
			$lastleaves_year = $row['lastyear_leaves'];
			$year = date("Y") - date("Y", strtotime($row['startdate']));
					if ($year <= 5){
							if($year ==0){
									$curryear_leaves = 12 - date("n", strtotime($row['startdate']));
									}
							else{
									$curryear_leaves = 12;
								}
							}
					elseif((6<=$year) && ($year<=9)){
							$curryear_leaves = 14;
							}
					else {
							$curryear_leaves = 16;
						}
		}
  // tong so ngay phep trong nam = tong ngay phep nam nay + ngay phep con lai cua nam truoc
if(isset($_POST['save'])){
	$id = $_POST['staffid'];
	$frdate = $_POST['fromdate'];
	$tdate = $_POST['todate'];
	$reason = $_POST['due_to'];
	$ap = $_POST['ap'];
	$st = $_POST['status'];
	$linkback = $_POST['linkback'];
	if($st == true)
	{
		$status = 'y';
	}
	else
	{
		$status = 'n';
	}
	if (($id=="")||($frdate=="")){
		echo "please fill information !";
		}
	else{	
		pg_query($connection, "INSERT INTO leaves(staff_id,fromdate,todate,reason,a_p,status) VALUES('$id','$frdate','$tdate','$reason','$ap','$status')");
		if($linkback == 1){
		    header("location:leaves.php?id=$id");
		}
		else{
		    header("location:welcome.php?3&&id=$id");
		}
	  }

}
echo '<form name="form1" method="post" action="welcome.php?3">';
echo "<h3>Search Staff's Leaves Day Information</h3>";
echo '<table>';
echo '<input type="radio" name="type" value="op_id">Staff ID<br>';
echo "<input type='radio' name='type' value='op_name'>Staff's Name<br>";
echo '<input type="text" name="txttim"><input type="submit" name="search" value="Search">';
echo '</table>';
echo '<hr>';
echo '<table width="100%">';
echo "<tr>";
echo '<b>Enter Leaves </b>';
echo "<td valign = 'top'>";
echo '<table>';
echo '<tr>'; 
echo '<td>Staff ID : </td>';
echo '<td><input type="text" name="staffid" readonly value="'.$staffid.'"></td>';
echo '</tr>';
echo '<tr>';
echo "<td>Staff's Name : </td>";
echo '<td><input type="text" name="staffname" readonly value="'.$name.'"></td>';
echo '</tr>';
echo '<tr>';
echo '<td>From : </td>';
echo '<td><input type="text" name="fromdate" onfocus="JavaScript:showCalendarControl(this);" value = "'.$from_date.'"><input type="radio" name="ap" value="1">AM</td>';
echo '</tr>';
echo '<tr>';
echo '<td>To : </td>';
echo '<td><input type="text" name="todate" onfocus="JavaScript:showCalendarControl(this);"><input type="radio" name="ap" value="1">PM</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Due To</td>';
echo '<td>';
echo '<select name="due_to">';
echo '<option>Sick</option>';
echo '<option>Vacation</option>';
echo '<option>Private matter</option>';
echo '<option>On Celebratory occation</option>';
echo '<option>On Condolance occation</option>';
echo '<option>Suspension from work</option>';
echo '<option>SH</option>';
echo '<option>AH</option>';
echo '<option>Others</option>';
echo '</select>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td>Approved : <input type = "checkbox" name = "status" value="true"></td>';
echo '</tr>';
echo '<tr><td>Detail : </td>';
echo '<td><textarea cols="40" rows="5" name="reason"></textarea></td>';
echo '</tr>';
echo '<tr><td><input type="submit" name="save" value="Save"></td>';
echo '<td>';
if ($linkback == 1){
  echo "<a href = 'search_att.php?id=$staffid&name=$name&month=$month'><< Back to attendance record</a>";
  echo "<input type = 'hidden' name='linkback' value='$linkback'>";
}
echo '</td></tr>';
echo '</table>';
echo "</td>";
echo "<td valign='top'>";
// gop phan nay thanh mot module roi link vao cac trang khac
echo '<table name="list_leaves" border="0">';
		echo "<tr bgcolor='#C7E2E2'>";
		echo '<b>List of Leaves days in '.date("Y").'</b>';
                echo "<td>From </td>";
                echo "<td>To </td>";
		echo "<td>Leaves day</td>";
		echo "<td>Reason </td>";
		echo "<td>Status</td>";
                echo "<td>Control </td>";
                echo "</tr>";
                $sql_str = "SELECT * FROM leaves WHERE staff_id='$staffid' AND date_part('year', fromdate) = date_part('year', current_date) order by todate asc";
                $result=pg_query($connection, $sql_str);
                while($res=pg_fetch_array($result)){
							$bien = $res['id'];
							$a_p = $res['a_p'];
							$st_trunggian = $res['status'];
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
												$number_leaves = (($todate - $fromdate)+1) - 0.5;
												}
										else{
												$number_leaves = ($todate - $fromdate)+1;
											}
							    }
							// moc thoi gian de tinh phep
							$mocthoigian = date("Y-06-21");
							if(mktime($res['todate']) <= mktime($mocthoigian)){
							  $tongngaydanghiphep_trong6thang = $tongngaydanghiphep_trong6thang + $number_leaves;
							}
							else{
							  $tongngaydanghiphep_ngoai6thang = $tongngaydanghiphep_ngoai6thang + $number_leaves;
							}
							if($st_trunggian == 'y'){
								$status_ = "applied";
							}
							else
							{
								$status_ = "not yet";
							}
							$subtotalleaves = $tongngaydanghiphep_ngoai6thang + $tongngaydanghiphep_trong6thang;
							echo "<tr>";
							echo "<td>".$res['fromdate']."</td>";
							echo "<td>".$res['todate']."</td>";
							echo "<td>".$number_leaves."</td>";
							echo "<td>".$res['reason']."</td>";
							echo "<td>".$status_."</td>";
							$lydo = $res['reason'];
echo "<td><a href=\"JavaScript:openURL_edit('edit_leaves.php?id1=$bien')\">Edit</a> |";
echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='del_leaves.php?id=$bien&staffid=$staffid';}\">Delete</a></td>";
							echo "</tr>";
                }
		if($tongngaydanghiphep_trong6thang <= $lastleaves_year){
		   $ngayphepconlai_namtruoc = $lastleaves_year - $tongngaydanghiphep_trong6thang;
		   $tongsongayphepconlai = $ngayphepconlai_namtruoc + ($curryear_leaves - $tongngaydanghiphep_ngoai6thang);
		}
		else{
		  $ngayphepconlai_namtruoc = 0;
		  $tongsongayphepconlai = $curryear_leaves - (($tongngaydanghiphep_trong6thang - $lastleaves_year)+$tongngaydanghiphep_ngoai6thang);
		}
				//echo '<tr>';
				//echo '<td colspan="2">';
				//echo 'Total Leaves from ' . date("21-1-Y") . ' To '. date("21-6-Y").' : ';
				//echo '</td>';
				//echo '<td>'.$tongngaydanghiphep_trong6thang.'</td>';
				//echo '</tr>';
				//echo '<tr>';
				//echo '<td colspan="2">';
				//echo 'Total Leaves from ' . date("22-6-Y") . ' To 21-1-'.(date("Y")+1) . ' : ';
				//echo '</td>';
				//echo '<td>'.$tongngaydanghiphep_ngoai6thang.'</td>';
				//echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Year ' . (date("Y")-1);
				echo '</td>';
				echo '<td>'.$lastleaves_year.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Balance Year ' . (date("Y")-1);
				echo '</td>';
				echo '<td>'.$ngayphepconlai_namtruoc.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Total Leaves in :' .(date("Y"));
				echo '</td>';
				echo '<td>'.$subtotalleaves.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Year ' . date("Y");
				echo '</td>';
				echo '<td>'.($curryear_leaves + $lastleaves_year).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Balance Year ' . date("Y");
				echo '</td>';
				echo '<td>'.$tongsongayphepconlai.'</td>';
				echo '</tr>';
				echo '</table>';
// gop den day
echo "</td>";
echo "</tr>";
echo '</form>';
count_leaves($staffid);
?>
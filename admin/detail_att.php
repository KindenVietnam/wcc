<?php
include("config.php");
include("ot.php");
$day = $_GET['day'];
$machine_no = $_GET['location'];
$sql_location = "select * from machine where machine_no = '$machine_no'";
$ketqua1 = pg_query($connection, $sql_location);
$hang = pg_fetch_object($ketqua1);
echo 'Attendance record statistic in ' .$day. '<br>';
echo 'Location : ' . $hang->name . '<br>';
echo '<table width="100%">';
echo "<tr bgcolor=#A9BCF5>";
echo '<td>Over Time</td>';
echo '<td>Staff ID</td>';
echo "<td>Staff's Name</td>";
echo '<td>Workday</td>';
echo '<td>In</td>';
echo '<td>Out</td>';
echo '</tr>';
$sql_detail = "select giochamcong.staff_id as staff_id,staff.name as name,giochamcong.workday as workday,
giochamcong.intime as intime,giochamcong.outtime as outtime
from staff,(
select staff_id,date(inout.checktime) as workday,
					to_char(min(checktime), 'HH24:MI:SS') as intime,
					to_char(max(checktime), 'HH24:MI:SS') as outtime
from inout
where machine_no = '$machine_no' and date(checktime) = '$day'
group by workday,machine_no,staff_id
) giochamcong
where staff.staff_id = giochamcong.staff_id";
$ketqua2 = pg_query($connection, $sql_detail);
while($hang = pg_fetch_array($ketqua2)){
		$in = $hang['intime'];
		$out = $hang['outtime'];
		if((hoursToMinutes('9:00') <= hoursToMinutes($out))&&(hoursToMinutes($out) <= hoursToMinutes('23:59'))){//gio ra ve
					$outtime =$out;
					}
		else{
				$outtime = '0:00';
				}
		if((hoursToMinutes('0:01') <= hoursToMinutes($in))&&(hoursToMinutes($in) <= hoursToMinutes('14:10'))){//gio vao lam viec
					$intime = $in;
				}
		else{
					$intime = '0:00';
				}
		echo '<tr>';
		echo '<td><input type="checkbox" name="ch_ovettime" value="1"></td>';
		echo '<td>'.$hang['staff_id'].'</td>';
		echo '<td>'.$hang['name'].'</td>';
		echo '<td>'.$hang['workday'].'</td>';
		echo "<td>".$intime."</td>";
		echo "<td>".$outtime."</td>";
		echo "</tr>";
	}
	echo '</table>';
?>
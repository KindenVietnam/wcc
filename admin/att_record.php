<link rel="stylesheet" type="text/css" href="table.css"/>
<?php
include("config.php");
include("ot.php");
$username = $_GET['id'];
$month = $_GET['month'];
$name = $_GET['name'];
echo "<table width='100%' border=0>";
echo "<td>";
echo "<h2>Attendance Record</h2>";
echo "</td>";
echo "<td>";
echo "<h2>".date("$month-Y")."</h2>";
echo "</td>";
$last_m = $month-1;
echo "<td>";
echo "<h4>From ".date("21-$last_m")." to ".date("20-$month-Y")."</h4>";
echo "</td>";
echo "</table>";
echo "<table width='100%' border=0 id='mtb'>";//noi dung report
echo "<tr>";
echo "<td colspan='2' rowspan=3>Date</td>";
echo "<td colspan='1' rowspan=3 style='width:70px;'>Attendance Mark</td>";
echo "<td colspan='6'>Over Time</td>";
echo "<td rowspan='3'>Detail Work</td>";
echo "<td colspan=2'>Working Time</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=2>Weekday</td>";
echo "<td colspan=2>Holiday</td>";
echo "<td colspan=2>Public Holiday</td>";
echo "<td rowspan=2 style='width:80px'>IN</td>";
echo "<td rowspan=2 style='width:80px'>OUT</td>";
echo "</tr>";
echo "<tr>";
echo "<td style='width:50px'>OT</td>";
echo "<td style='width:50px'>ST</td>";
echo "<td style='width:50px'>OT</td>";
echo "<td style='width:50px'>ST</td>";
echo "<td style='width:50px'>OT</td>";
echo "<td style='width:50px'>ST</td>";
echo "</tr>";
$ah = 1;
$curr_month = $month;
$last_year = date("Y")-1;
$curr_year = date("Y");
if($curr_month == 1){
	 $last_month = 12;
	// $last_year = date("Y")-1;
	 $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year); 
}
else{
	 $last_month = $month - 1;
	// $curr_year = date("Y");
	 $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year);
}
switch ($days_in_last_month) {
    case 31:
        $rows = 51;
        break;
    case 30:
        $rows = 50;
        break;
    case 29:
        $rows = 49;
        break;
    case 28:
	$rows = 48;
	break;
}
for ($i=21;$i<=$rows;$i++)
     {
	if ($i <= $days_in_last_month)
	  {
		if ($curr_month == 1){
		        $date = date("$i-$last_month-$last_year");
		        $c_date = date("$last_year-$last_month-$i");
		  }
	        else{
		       $date = date("$i-$last_month-Y");
		       $c_date = date("Y-$last_month-$i");  
		  }
          }
	else
          {
		switch ($days_in_last_month){
		case 31:{
			$n = $i - 31;
			$date = date("$n-$curr_month-Y");
			$c_date = date("Y-$curr_month-$n");
			break;
			}
		case 30:{
			$n = $i - 30;
			$date = date("$n-$curr_month-Y");
			$c_date = date("Y-$curr_month-$n");
			break;
			}
		case 29:{
			$n = $i - 29;
			$date = date("$n-$curr_month-Y");
			$c_date = date("Y-$curr_month-$n");
			break;
			}
		case 28:{
			$n = $i - 28;
			$date = date("$n-$curr_month-Y");
			$c_date = date("Y-$curr_month-$n");
			break;
			}
		}
	  }
$sql1 = "select staff_id,date(checktime) as workday,
       to_char(min(checktime), 'HH24:MI:SS') as intime,
       to_char(max(checktime), 'HH24:MI:SS') as outtime
from inout
where staff_id = '$staffid' and date(checktime) = '$c_date'
group by staff_id,workday
order by workday";
$sql_overtime = "select * from overtime where staff_id = '$staffid' and workday = '$c_date'";
$wday = date("D",strtotime($date));
$result1 = pg_query($connection, $sql1);
$result_overtime = pg_query($connection, $sql_overtime);
$row_overtime = pg_fetch_object($result_overtime);
$row1 = pg_fetch_object($result1);
$att_mark = $row_overtime->att_mark;
$ot_w = $row_overtime->wot;
$st_w = $row_overtime->wst;
$ot_h = $row_overtime->hot;
$st_h = $row_overtime->hst;
$ot_oh = $row_overtime->pot;
$st_oh = $row_overtime->pst;
$detail = $row_overtime->detailwork;
$in = $row1->intime;
$out = $row1->outtime;
if((hoursToMinutes('9:00') <= hoursToMinutes($out))&&(hoursToMinutes($out) < hoursToMinutes('23:59'))){//gio ra ve
		$outtime =$out;
		}
else{
	$outtime = '0:00';
	}
if((hoursToMinutes('0:01') < hoursToMinutes($in))&&(hoursToMinutes($in) <= hoursToMinutes('14:10'))){//gio vao lam viec
		$intime = $in;
	}
else{
	$intime = '0:00';
	}
	echo "<tr>";
	echo "<td style='width: 70px;'><a href='update_worktime.php?id=$username&day=$c_date&name=$name&in=$intime&out=$outtime'>".date("j/n/Y",strtotime($date))."</a></td>";
	echo "<td style='width: 70px'>".$wday."</td>";
	echo "<td>".$att_mark."</td>";
	echo "<td>".$ot_w."</td>";
	echo "<td>".$st_w."</td>";
	echo "<td>".$ot_h."</td>";
	echo "<td>".$st_h."</td>";
	echo "<td>".$ot_oh."</td>";
	echo "<td>".$st_oh."</td>";
    echo "<td style='width:180px'>".$detail."</td>";
	echo "<td>".blank_space($intime)."</td>";
	echo "<td>".blank_space($outtime)."</td>";
	echo "</tr>";
   }//het vong lap tao report
echo "</table>";
?>

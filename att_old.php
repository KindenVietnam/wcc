<link rel="stylesheet" type="text/css" href="table.css"/>
<body><center>
<?php
session_start();
include("config.php");
include("ot.php");
$username = $_SESSION['id'];
echo "<table width='100%' border=0 id='head'>";
echo "<td>";
echo "<h2>Attendance Record</h2>";
echo "</td>";
echo "<td>";
echo "<h2>".date("M-Y")."</h2>";
echo "</td>";
$last_m = date("n")-1;
echo "<td>";
echo "<h4>From ".date("21-$last_m")." to ".date("20-n-Y")."</h4>";
echo "</td>";
echo "</table>";
echo "________________________________________________________________________________________________________________________________";//dong ke
echo "<table width='100%' border=0 id='ahead'>";
echo "<td><h5>Applicant</h5></td>";
$sql = "SELECT * FROM staff WHERE staff_id = '$username'";
	$result = pg_query($connection, $sql);
	while($row = pg_fetch_object($result))
		{
			 echo "<td><h5>".$row->staff_id."</h5></td>";
			 echo "<td><h5>".$row->name."</h5></td>";
		}
echo "</table>";
echo "<table width='100%' border=0 id='mtb'>";//noi dung report
echo "<tr>";
echo "<td colspan='2' rowspan=3>Date</td>";
echo "<td colspan='1'rowspan=3 style='width: 10px;'>Attendance Mark</td>";
echo "<td colspan='6'>Over Time</td>";
echo "<td colspan=2'>Working Time</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=2>Weekday</td>";
echo "<td colspan=2>Holiday</td>";
echo "<td colspan=2>Public Holiday</td>";
echo "<td rowspan=2>IN</td>";
echo "<td rowspan=2>OUT</td>";
echo "</tr>";
echo "<tr>";
echo "<td>OT</td>";
echo "<td>ST</td>";
echo "<td>OT</td>";
echo "<td>ST</td>";
echo "<td>OT</td>";
echo "<td>ST</td>";
echo "</tr>";
$ah = 1;
	$curr_month = date("n");
			$curr_year = date("Y");
			$curr_day = date("j");
			if ($curr_month == 1){
					$last_month = 12;
					$last_year = date("Y")-1;
					$days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year); 
					}
			else{
				$last_month = date("n")-1;
				$days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year); 
				}
			$total_rows = $curr_day + $days_in_last_month;
//$days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year); 
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
							$c_date = date("$last_year-$last_month-$i");
							$date = date("$i-$last_month-$last_year");
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
where staff_id = '$username' and date(checktime) = '$c_date'
group by staff_id,workday
order by workday";
$sql2 = "select holiday from public_holiday where holiday='$c_date'";
$sql3 = "select staff_id,fromdate,todate from leaves where fromdate = '$c_date' and staff_id = '$username'";
$wday = date("D",strtotime($date));
$result1 = pg_query($connection, $sql1);
$result2 = pg_query($connection, $sql2);
$result3 = pg_query($connection, $sql3);
$row1 = pg_fetch_object($result1);
$row2= pg_fetch_object($result2);
$row3 = pg_fetch_object($result3);
$h = $row2->holiday;
$in = $row1->intime;
$out = $row1->outtime;
if((hoursToMinutes('9:00') <= hoursToMinutes($out))&&(hoursToMinutes($out) < hoursToMinutes('23:59'))){//gio ra ve
		$outtime =hoursToMinutes($out);
		}
else{
	$outtime = 0;
	}
if((hoursToMinutes('0:01') < hoursToMinutes($in))&&(hoursToMinutes($in) <= hoursToMinutes('14:30'))){//gio vao lam viec
		$intime = hoursToMinutes($in);
	}
else{
	$intime = 0;
	}
if(($wday =='Sun')||(strlen($h)>0)){ // tinh ngay publich holiday
		if(($wday =='Sun')&&(strlen($h)>0)){
					$att_mark = 'OH';
				}
		elseif(strlen($h)>0){
					$att_mark = 'OH';
				}
		else{
			$att_mark = 'H';
			}
          }
elseif($wday == 'Sat'){//tinh ngay AH
			if ((hoursToMinutes($intime)==hoursToMinutes('0:00'))&&(hoursToMinutes($outtime)==hoursToMinutes('0:00')))
				{
					if($ah <= 2){
						$ah = $ah + 1;
						$att_mark = 'AH';
					}
				}
			else{
				   if(($ah <= 2)&&($n>0)){
					$ah = $ah + 1;
					$att_mark = 'AH';
					}
				}
		     }
else{
	$att_mark = 'O';
	}
if(($intime == 0)&&($outtime == 0 )){//absent and leaves
		if($att_mark == 'O'){
			if(strlen($row3->staff_id)<>0){
							$att_mark = 'L';
							$fromdate = date("j",strtotime($row3->fromdate));
							$todate = date("j",strtotime($row3->todate));
							if (($todate - $fromdate)==0){
										$number_leaves = 1;
										}
							else{
									$number_leaves = $todate - $fromdate;
								}
							$subtotalleaves = $subtotalleaves + $number_leaves;
					}
			else{
					$att_mark = 'X';
				}
		}
	}
if ($att_mark == 'OH')//tinh ot va st
		{
		if(hoursToMinutes($out) < hoursToMinutes('17:00'))
						{
						if(hoursToMinutes($out) <= hoursToMinutes('12:00'))//lam buoi sang
							{
								$ot_oh = hoursToMinutes($out) - hoursToMinutes($in);	
							}
						else{
							if (hoursToMinutes($in)<=hoursToMinutes('11:00')){
								$ot_oh = (hoursToMinutes($out) - hoursToMinutes($in))-60;
								}
							else{
								$ot_oh = (hoursToMinutes($out) - hoursToMinutes($in));
							    }
							}	
						}
		else{
			if(hoursToMinutes($out) > hoursToMinutes('22:00')){
				$st_oh = st($out);
				if(hoursToMinutes($in)<=hoursToMinutes('11:00')){
				$ot_oh = (hoursToMinutes($out) - hoursToMinutes($in))-60;
					}
				else{
				$ot_oh = (hoursToMinutes($out) - hoursToMinutes($in));
				}
			    }
			else {
			 	if(hoursToMinutes($in)<=hoursToMinutes('11:00')){
				$ot_oh = (hoursToMinutes($out) - hoursToMinutes($in))-60;
					}
				else{
				$ot_oh = (hoursToMinutes($out) - hoursToMinutes($in));
				}
			     }
			}
			$ot_w = 0;
			$st_w = 0;
			$ot_h = 0;
			$st_h = 0;	
		}
elseif (($att_mark == 'H')||($att_mark == 'AH')){
	if(hoursToMinutes($out) < hoursToMinutes('17:00'))
						{
						if(hoursToMinutes($out) <= hoursToMinutes('12:00'))//lam buoi sang
							{
						$ot_h = hoursToMinutes($out) - hoursToMinutes($in);	
							}
						else{
							$ot_h = (hoursToMinutes($out) - hoursToMinutes($in))-60;
							}	
						}
	else{
		if(hoursToMinutes($out) > hoursToMinutes('22:00')){
				$st_h = st($out);
				if(hoursToMinutes($in)<hoursToMinutes('11:00')){//lam buoi chieu
				$ot_h = (hoursToMinutes($out) - hoursToMinutes($in))-60;
					}
				else{
				$ot_h = (hoursToMinutes($out) - hoursToMinutes($in));
				}
			}
		else {
			if(hoursToMinutes($in)<hoursToMinutes('11:00')){//lam buoi chieu
				$ot_h = (hoursToMinutes($out) - hoursToMinutes($in))-60;
					}
			else{
				$ot_h = (hoursToMinutes($out) - hoursToMinutes($in));
				}
			}
		}
		$ot_w = 0;
		$st_w = 0;
		$ot_oh = 0;
		$st_oh = 0;	
	}
else {
	if ((hoursToMinutes('17:00') < hoursToMinutes($out))&&(hoursToMinutes($out) <= hoursToMinutes('22:00'))){
				$ot_w = ot($out);
	    }
	elseif (hoursToMinutes('22:00') < hoursToMinutes($out)){
		$ot_w = ot('22:00');
		$st_w = st($out);
		}
	else {
		$ot_w = 0;
		$st_w = 0;
             }
       $ot_oh = 0;
       $st_oh = 0;
       $ot_h = 0;
       $st_h = 0;
     }
if($att_mark == 'O'){
		$total_O = $total_O +1;
	}
if($att_mark == 'OH'){
		   $total_OH = $total_OH +1;
		}
if((($att_mark == 'H')||($att_mark == 'AH'))&&((hoursToMinutes($in)<>0)||(hoursToMinutes($out)<>0))){
			$total_H = $totalH + 1;
	    }
if($att_mark == 'X'){
			$total_absent = $total_absent + 1;
			}
	$total_ot_oh = $total_ot_oh + $ot_oh;
	$total_st_oh = $total_st_oh + $st_oh;
	$total_ot_h = $total_ot_h + $ot_h;
	$total_st_h = $total_st_h + $st_h;
	$total_ot_w = $total_ot_w + $ot_w;
	$total_st_w = $total_st_w + $st_w;
	echo "<tr>";
	echo "<td style='width: 12px;'>".$date."</td>";
	echo "<td>".$wday."</td>";
	echo "<td>".$att_mark."</td>";
	echo "<td>".blank_space($ot_w)."</td>";
	echo "<td>".blank_space($st_w)."</td>";
	echo "<td>".blank_space($ot_h)."</td>";
	echo "<td>".blank_space($st_h)."</td>";
	echo "<td>".blank_space($ot_oh)."</td>";
	echo "<td>".blank_space($st_oh)."</td>";
	echo "<td>".blank_space($intime)."</td>";
	echo "<td>".blank_space($outtime)."</td>";
	echo "</tr>";
   }//het vong lap tao report
	echo "<tr>";
	echo "<td colspan=3>Total(hrs)</td>";
	echo "<td>".blank_space($total_ot_w)."</td>";
	echo "<td>".blank_space($total_st_w)."</td>";
	echo "<td>".blank_space($total_ot_h)."</td>";
	echo "<td>".blank_space($total_st_h)."</td>";
	echo "<td>".blank_space($total_ot_oh)."</td>";
	echo "<td>".blank_space($total_st_oh)."</td>";
	echo "</tr>";
$totalworkingday = $total_O + $total_H + $subtotalleaves + $total_OH;
echo "</table>";
echo "<table id='footer'>";
echo "<tr>";
echo "<td><h4> Status of working</h4>";
echo "<table id='sub'>";
echo "<tr>";
echo "<td>";
echo "</td>";
echo "<td>Days";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>O: Attendance";
echo "</td>";
echo "<td>".$total_O."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>X: Absent";
echo "</td>";
echo "<td>".$total_absent."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>L: Leaves";
echo "</td>";
echo "<td>".$subtotalleaves."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>OH: Holiday";
echo "</td>";
echo "<td>".$total_OH."</td>";
echo "</tr>";
echo "<tr>";
echo "<td>SH: Other Special holiday";
echo "</td>";
echo "<td>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Total working days";
echo "</td>";
echo "<td>".$totalworkingday."</td>";
echo "</tr>";
echo "</table>";
echo "</td>";
echo "<td valign='top' align='right'><h4>Job-Performance Condition(Evalliate by your Manager)</h4>";
echo "Only in case of change for his/her working conditions, enter here</br>";
echo "<table id='sub'>";
echo "<tr>";
echo "<td>Working Condition :</td>";
echo "<td>_____________________________________________</td>";
echo "</tr>";
echo "<tr>";
echo "<td>Efficiency & Quality :</td>";
echo "<td>______________________________________________</td>";
echo "</tr>";
echo "</table>";
echo "</td>";
echo "</tr>";
echo "</tr>";
echo "</table>";
?>
</center></body>
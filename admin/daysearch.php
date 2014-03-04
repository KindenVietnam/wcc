<?php
session_start();
include("config.php");
include("ot.php");	
			$username = $_SESSION['id'];
			echo "<table width='100%' border=0 id='tb1'>";
			echo "<tr bgcolor=#F0F7F7>";
			$c_date1 = date('Y-n-j');
			$wday2 = date("D",strtotime($c_date1));
			echo "<td><h2>".date('Y-n-j')."</h2></td>";
			$sql2 = "select staff_id,date(checktime) as workday,
			       to_char(min(checktime), 'HH24:MI:SS') as intime,
			      to_char(max(checktime), 'HH24:MI:SS') as outtime
			from inout
			where staff_id = '$username' and date(checktime) = '$c_date1'
			group by staff_id,workday
			order by workday";
			$result2 = pg_query($connection, $sql2);
			$row2 = pg_fetch_object($result2);
			$in2 = $row2->intime;
			$out2 = $row2->outtime;
	                if((hoursToMinutes('10:00') < hoursToMinutes($out2))&&(hoursToMinutes($out2) < hoursToMinutes('24:00'))){
		                   $outtime2 = $out2;
		                   }
                        else{
	                        $outtime2 = '0:00';
	                      }
                        if((hoursToMinutes('4:00') < hoursToMinutes($in2))&&(hoursToMinutes($in2) < hoursToMinutes('13:10'))){
		                $intime2 = $in2;
	                      }
                        else{
	                        $intime2 = '0:00';
	                     }
				echo "<td>".$wday2."</td>";
				echo "<td>".$intime2."</td>";
				echo "<td>".$outtime2."</td>";
			echo "</tr>";
			echo "<tr bgcolor='#A9BCF5'>";
			echo "<td>Date</td>";
			echo "<td>Weekday</td>";
			echo "<td>IN</td>";
			echo "<td>OUT</td>";
			echo "</tr>";
			$last_month = date("n") - 1;
			$curr_month = date("n");
			$curr_year = date("Y");
			$days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year); 
			$curr_day = date("j");
			$total_rows = $curr_day + $days_in_last_month;
			for ($i=20;$i<=$total_rows;$i++)
			     {
				if ($i <= $days_in_last_month)
	 				 {
						$c_date = date("Y-$last_month-$i");
          				  }
				else{
					switch ($days_in_last_month){
						case 31:{
							$n = $i - 31;
							$c_date = date("Y-$curr_month-$n");
							break;
							}
						case 30:{
							$n = $i - 30;
							$c_date = date("Y-$curr_month-$n");
							break;
							}
						case 29:{
							$n = $i - 29;
							$c_date = date("Y-$curr_month-$n");
							break;
							}
						case 28:{
							$n = $i - 28;
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
			$wday = date("D",strtotime($c_date));
			$result1 = pg_query($connection, $sql1);
			$row1 = pg_fetch_object($result1);
			$in = $row1->intime;
			$out = $row1->outtime;
if((hoursToMinutes('11:00') <= hoursToMinutes($out))&&(hoursToMinutes($out) < hoursToMinutes('23:00'))){//gio ra ve
		$outtime =hoursToMinutes($out);
		}
else{
	$outtime = '0:00';
	}
if((hoursToMinutes('7:00') < hoursToMinutes($in))&&(hoursToMinutes($in) <= hoursToMinutes('13:10'))){//gio vao lam viec
		$intime = hoursToMinutes($in);
	}
else{
	$intime = '0:00';
	}
				echo "<tr>";
				echo "<td>".$c_date."</td>";
				echo "<td>".$wday."</td>";
				echo "<td>".blank_space($intime)."</td>";
				echo "<td>".blank_space($outtime)."</td>";
				echo "</tr>";
	}
			echo "</table>";
		
?>

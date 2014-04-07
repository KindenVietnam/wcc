<?php

include_once("ot.php");
function view_att_form($month,$staffid){	
		include('config.php'); 
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
	      $curr_month = $month;
	      $last_year = date("Y")-1;
	      $curr_year = date("Y");
	      if($curr_month == 1){
		       $last_month = 12;
		       $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year);
	      }
	      else{
		       $last_month = $month - 1;
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
	      $mang_in = array();//mang chua du lieu in
	      $mang_out = array();// mang chua du lieu out
	      $q = 0; // chi muc cua mang
	      for ($i=21;$i<=$rows;$i++){//gan gia tri cho mang
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
			$sql_inout = "select staff_id,date(checktime) as workday,
			       to_char(checktime, 'HH24:MI:SS') as giochamcong
			from inout
			where staff_id = '$staffid' and date(checktime) = '$c_date' order by checktime asc";
			$ketqua = pg_query($connection, $sql_inout);
			while($row_inout = pg_fetch_object($ketqua)){ // tinh in out
				$chamcong = $row_inout->giochamcong;
				if(hoursToMinutes($chamcong) < hoursToMinutes('5:00')){
                                    // neu gio vao nho hon 5 gio thi cong sang ngay hom sau
					$mang_out[$q-1] = minutesToHours(hoursToMinutes('24:00')+hoursToMinutes($chamcong));
				}
				if((hoursToMinutes($chamcong) > hoursToMinutes('5:00'))&&(hoursToMinutes($chamcong)<hoursToMinutes('14:00'))){
                                    // lay gio in trong khoang 5 gio den 14 gio
                                    if(hoursToMinutes($mang_in[$q])>0){
                                        if(hoursToMinutes($mang_in[$q]) > hoursToMinutes($chamcong)){
                                            // neu gio vao tiep theo nho hon gio vao truoc lay gio vao tiep theo
                                            $mang_in[$q] = $chamcong;
                                        }
                                        else{
                                            // nguoc lai khong thay doi gia tri
                                            $mang_in[$q] = $mang_in[$q];
                                        }
                                    }
                                    else{
                                        $mang_in[$q] = $chamcong;
                                    }
				}
				if(hoursToMinutes($chamcong)>hoursToMinutes('10:00')){
                                    // lay gio out trong khoang tu 12 gio
					$mang_out[$q] = $chamcong;
				}
			}
			$q=$q+1;
	      }
	      $f = 0;// khoi tao chi muc mang
	      for ($i=21;$i<=$rows;$i++) // hien thi du lieu
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
               $sql_overtime = "select * from overtime where staff_id = '$staffid' and workday = '$c_date'";
	       $sql_leaves = "select count(fromdate) as leaves,a_p,reason from leaves where staff_id = '$staffid' and fromdate <= '$c_date' and '$c_date' <= todate group by fromdate,a_p,reason";
               $wday = date("D",strtotime($date));
               $result1 = pg_query($connection, $sql1);
               $result_overtime = pg_query($connection, $sql_overtime);
               $row_overtime = pg_fetch_object($result_overtime);
               $row1 = pg_fetch_object($result1);
	       $result_leaves = pg_query($connection, $sql_leaves);
	       $row_leaves = pg_fetch_object($result_leaves);
               $att_mark = $row_overtime->att_mark;
               $ot_w = $row_overtime->wot;
               $st_w = $row_overtime->wst;
               $ot_h = $row_overtime->hot;
               $st_h = $row_overtime->hst;
               $ot_oh = $row_overtime->pot;
               $st_oh = $row_overtime->pst;
               $detail = $row_overtime->detailwork;
	       $nghiphep_nuangay = $row_leaves->a_p;
	       $reason = $row_leaves->reason;
	       $ketqua_nghiphep = $row_leaves->leaves;
	      if($ketqua_nghiphep >= 1){
	        if(strlen(trim($reason))==strlen('AH')){
		     $att_mark = 'AH';
			}
			else{
		     if($nghiphep_nuangay == 1){
			     $att_mark = "1/2L";
			 }
		     else{
				$att_mark = "L";
				}
		     }
	       }
	      if(hoursToMinutes($mang_in[$f]) == hoursToMinutes($mang_out[$f])){
				if(hoursToMinutes($mang_in[$q]) < hoursToMinutes('12:00')){
					$mang_out[$f] = '';
				}
				else{
					$mang_in[$f] = '';
				}
		     }
		      echo "<tr>";
		      echo "<td style='width: 70px;'><a href='update_worktime.php?id=$staffid&day=$c_date&name=$name&in=$mang_in[$f]&out=$mang_out[$f]&month=$month&date=$c_date'>".date("j/n/Y",strtotime($date))."</a></td>";
		      echo "<td style='width: 70px'>".$wday."</td>";
		      echo "<td>".$att_mark."</td>";
		      echo "<td>".$ot_w."</td>";
		      echo "<td>".$st_w."</td>";
		      echo "<td>".$ot_h."</td>";
		      echo "<td>".$st_h."</td>";
		      echo "<td>".$ot_oh."</td>";
		      echo "<td>".$st_oh."</td>";
		      echo "<td style='width:180px'>".$detail."</td>";
		      echo "<td>".$mang_in[$f]."</td>";
		      echo "<td>".$mang_out[$f]."</td>";
		      echo "</tr>";
		     $f=$f+1;
		 }//het vong lap tao report
	      echo "</table>";
	}
?>
<link rel="stylesheet" type="text/css" href="table.css"/>
<style>
    table #mtb{
	text-align: center;
	font-size: 12px;
    }
</style>
<?php
$month = $_GET['thang'];
$manv = $_GET['manv'];
$status = $_GET['status'];
include_once('ot.php');
include_once('find_sat_in_month.php');
// check dang nhap
if($status == 1){
    att_view($manv,$month);
}
/*Ham hien thi bang cham cong chi tiet*/
function att_view($id,$thang){
    include('config.php');
    //include('ot.php');
    // check authen if it is already logined
    session_start();
	$username = $_SESSION['id'];
	if($username == 0){
			header("location:index.php");
		}
    $count_early = 0; // dem di muon
    $count_lately = 0; // dem ve som
    $count_o = 0; // dem ngay di lam bt
    $count_x = 0; // dem ngay nghi ko phep
    $count_l = 0; // dem ngay nghi phep
    $count_h = 0; // dem ngay nghi bao gom chu nhat
    $count_sh = 0; // dem ngay sh
    $sat = 0; // bo dem ngay thu 7 trong bang cham cong
    $total_w_ot = 0;
    $total_w_st = 0;
    $total_h_ot = 0;
    $total_h_st = 0;
    $total_sh_ot = 0;
    $total_sh_st = 0;
    $month = $thang;
    /*
    echo "-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
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
    echo "--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
    echo "</br>";
    echo "<strong>Applicant&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:".$id."</strong>";
    $sql_ten_nhan_vien = "select name from staff where staff_id = '$id'";
    $result_ten_nhanvien = pg_query($connection,$sql_ten_nhan_vien);
    $row_ten_nhan_vien = pg_fetch_array($result_ten_nhanvien);
    echo "<strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$row_ten_nhan_vien['name']."</strong>";
    echo "<strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspApplicant's Signature</strong>";
    */
    echo "<table border=0 id='mtb'>";//noi dung report
    echo "<tr>";
    echo "<td colspan='2' rowspan=3 style='width:100px;'>Date</td>";
    echo "<td colspan='1' rowspan=3 style='width:50px;'>Att Mark</td>";
    echo "<td colspan='6' style='width:180px;'>Over Time</td>";
    echo "<td rowspan='3' style='width:140px;'>Detail Work</td>";
    echo "<td colspan=2' style='width=120px;'>Working Time</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan=2 style='width:60px;'>Weekday</td>";
    echo "<td colspan=2 style='width:60px;'>Holiday</td>";
    echo "<td colspan=2 style='width:60px;'>Public Holiday</td>";
    echo "<td rowspan=2 style='width:60px'>IN</td>";
    echo "<td rowspan=2 style='width:60px'>OUT</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td style='width:30px'>OT</td>";
    echo "<td style='width:30px'>ST</td>";
    echo "<td style='width:30px'>OT</td>";
    echo "<td style='width:30px'>ST</td>";
    echo "<td style='width:30px'>OT</td>";
    echo "<td style='width:30px'>ST</td>";
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
   /* switch ($days_in_last_month) {
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
        }*/
   $rows = $days_in_last_month + date('j');
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
			where staff_id = '$id' and date(checktime) = '$c_date' order by checktime asc";
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
                                            //neu gio vao tiep theo nho hon gio vao truoc lay gio vao tiep theo
                                            $mang_in[$q] = $chamcong;
                                        }
                                        else{
                                            //nguoc lai khong thay doi gia tri
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
	       $sql_holiday = "select * from public_holiday where holiday = '$c_date'";
	       $sql_overtime = "select * from overtime where staff_id = '$id' and workday = '$c_date'";
	       $sql_leaves = "select count(fromdate) as leaves,a_p,reason from leaves where staff_id = '$id' and fromdate <= '$c_date' and '$c_date' <= todate group by fromdate,a_p,reason";
           $wday = date("D",strtotime($date));
	       $result_holiday = pg_query($connection,$sql_holiday);
           $result1 = pg_query($connection, $sql1);
           $result_overtime = pg_query($connection, $sql_overtime);
           $row_overtime = pg_fetch_object($result_overtime);
	       $result_leaves = pg_query($connection, $sql_leaves);
	       $row_leaves = pg_fetch_object($result_leaves);
	       $row_holiday = pg_fetch_object($result_holiday);
           $row1 = pg_fetch_object($result1);
	       $status_holiday = $row_holiday->sh;
	       $note = $row_holiday->note;
           $att_mark = $row_overtime->att_mark; // att mark mac dinh duoc lay tu bang overtime
               //$ot_w = $row_overtime->wot;
               //$st_w = $row_overtime->wst;
               //$ot_h = $row_overtime->hot;
               //$st_h = $row_overtime->hst;
               //$ot_oh = $row_overtime->pot;
              // $st_oh = $row_overtime->pst;
           $detail = $row_overtime->detailwork;
	       $nghiphep_nuangay = $row_leaves->a_p;
	       $reason = $row_leaves->reason;
	       $ketqua_nghiphep = $row_leaves->leaves;
		    // dem so ngay di muon ve som
		    if((strtotime($mang_in[$f]) > strtotime('8:05:00'))&&(strtotime($mang_in[$f]) > strtotime('0:00:00'))){
				   $count_lately = $count_lately + 1;
		    }
		    if((strtotime($mang_out[$f]) < strtotime('17:00:00'))&&(strtotime($mang_out[$f]) > strtotime('0:00:00'))){
				   $count_early = $count_early + 1;
		    }
		    // tao khoang trong
		   // if(hoursToMinutes($mang_in[$f]) == hoursToMinutes($mang_out[$f])){
		//		if(hoursToMinutes($mang_in[$q]) >= hoursToMinutes('12:00')){
		//			$mang_in[$f] = '12:00:00';
		//		}
		//		elseif(hoursToMinutes($mang_out[$q]) <= hoursToMinutes('12:00')){
		//			$mang_out[$f] = '12:00:00';
		//		}
		//		else{
		//			$mang_in[$f] = '';
		//			$mang_out[$f] = '';
		//		}
		  //   }
		    //chi tao att mark den ngay hom truoc
		    $ngayhomtruoc = date('j') - 1;
		    $ngaytao_att_mark = $ngayhomtruoc .'-'. date('n-Y');
		if(strtotime($date) <= strtotime($ngaytao_att_mark)){
		    // kiem tra xem ah da duoc dang ki o ngay nghi va overtime chua
		    if(strlen($note) > 0 ){
			if(strcmp(trim($sh),'sh') == 0){
			    $att_mark = 'SH';
			    $count_sh = $count_sh + 1;
			}
			else{
			    $att_mark = 'OH';
			    $count_h = $count_h +1;
			}
		    }
		    elseif($ketqua_nghiphep >= 1){
			//nghi phep ah
			if(strcmp(trim($reason),'AH') == 0){
			    if($nghiphep_nuangay == 1){
				$att_mark = "1/2 AH";
			       }
			   else{
				 $att_mark = "AH";
			       }
			}
			//nghi phep sh
			elseif(strcmp(trim($reason),'SH') == 0){
			    if($nghiphep_nuangay == 1){
				$att_mark = "1/2 SH";
			       }
			    else{
				 $att_mark = "SH";
				 $count_sh = $count_sh + 1;
			       }
			}
			else{
			    // nghi phep nam
			   if($nghiphep_nuangay == 1){
				$att_mark = "1/2L";
				$count_l = $count_l + 0.5;
				$count_o = $count_o + 0.5;
			       }
			   else{
			       $att_mark = "L";
			       $count_l = $count_l + 1;
			       }
			}
		    }
		    //ah dc dang ki lam overtime 
		    elseif(strcmp(trim($att_mark),'AH') == 0){
			$att_mark = 'AH';
		     }
		    elseif(strcmp(trim($att_mark),'1/2AH') == 0){
			$att_mark = '1/2 AH';
		     }
		     // ngay thuong va thu 7 di lam va chu nhat
		    else{
			if(strcmp(trim($wday),'Sun') == 0){
			    $att_mark = 'H';
			}
			// xu ly ngay thu 7 khong nghi ah khong lam them ah
			elseif(strcmp(trim($wday),'Sat') == 0){
			    $sat = $sat + 1;
			    if(count_sat($month) == 4){// trong khoang thoi gian cham cong co 4 thu 7
				    if($sat > 2){
				       $att_mark = 'AH';
				      }
				    else{
						if((strlen($mang_in[$f]) == 0 )&&(strlen($mang_out[$f])== 0)){
						$att_mark = 'X';
						$count_x = $count_x +1;
					    }
					    elseif((strtotime($mang_in[$f]) >= strtotime('12:00:00'))||(strtotime($mang_out[$f]) <= strtotime('12:00:00'))){
						$att_mark = '1/2 X';
						$count_x = $count_x + 0.5;
						$count_o = $count_o + 0.5;
					    }
					    elseif((strlen(trim($mang_in[$f])) == 0)||(strlen(trim($mang_out[$f]))==0)){
						$att_mark = '1/2 X';
						$count_x = $count_x + 0.5;
						$count_o = $count_o + 0.5;
					    }
					    elseif((strtotime($mang_in[$f]) >= strtotime('8:30:00'))||(strtotime($mang_out[$f]) <= strtotime('16:30:00'))){
						$att_mark = '1/2 O';
						$count_o = $count_o + 0.5;
					    }
					    else{
						$att_mark = 'O';
						$count_o = $count_o + 1;
					    }
					}
			    }
			    else{//trong khoang thoi gian cham cong co 5 thu 7
				if($sat > 3){
				    $att_mark = 'AH';
				}
				else{
				    	    if((strlen($mang_in[$f]) == 0 )&&(strlen($mang_out[$f])== 0)){
						$att_mark = 'X';
						$count_x = $count_x +1;
					    }
					    elseif((strtotime($mang_in[$f]) >= strtotime('12:00:00'))||(strtotime($mang_out[$f]) <= strtotime('12:00:00'))){
						$att_mark = '1/2 X';
						$count_x = $count_x + 0.5;
						$count_o = $count_o + 0.5;
					    }
					    elseif((strlen(trim($mang_in[$f])) == 0)||(strlen(trim($mang_out[$f]))==0)){
						$att_mark = '1/2 X';
						$count_x = $count_x + 0.5;
						$count_o = $count_o + 0.5;
					    }
					    elseif((strtotime($mang_in[$f]) >= strtotime('8:30:00'))||(strtotime($mang_out[$f]) <= strtotime('16:30:00'))){
						$att_mark = '1/2 O';
						$count_o = $count_o + 0.5;
					    }
					    else{
						$att_mark = 'O';
						$count_o = $count_o + 1;
					    }
				}
			    }
			}
			//ngay thuong 
			else{
			    if((strlen($mang_in[$f]) == 0 )&&(strlen($mang_out[$f])== 0)){
				$att_mark = 'X';
				$count_x = $count_x +1;
			    }
			    elseif((strtotime($mang_in[$f]) >= strtotime('12:00:00'))||(strtotime($mang_out[$f]) <= strtotime('12:00:00'))){
				$att_mark = '1/2 X';
				$count_x = $count_x + 0.5;
				$count_o = $count_o + 0.5;
			    }
			    elseif((strlen(trim($mang_in[$f])) == 0)||(strlen(trim($mang_out[$f]))==0)){
				$att_mark = '1/2 X';
				$count_x = $count_x + 0.5;
				$count_o = $count_o + 0.5;
			    }
			    elseif((strtotime($mang_in[$f]) >= strtotime('8:30:00'))||(strtotime($mang_out[$f]) <= strtotime('16:30:00'))){
				$att_mark = '1/2 O';
				$count_o = $count_o + 0.5;
			    }
			    else{
				$att_mark = 'O';
				$count_o = $count_o + 1;
			    }
			}
		     }
		   }
		      echo "<tr>";
		      echo "<td style='width:50px;'>".date("j/n",strtotime($date))."</td>";
		      echo "<td style='width:50px;'>".$wday."</td>";
		      echo "<td style='width:50px;'>".''."</td>";
		      echo "<td style='width:20px;'>".$ot_w."</td>";
		      $total_w_ot = $ot_w + $total_w_ot;
		      echo "<td style='width:20px;'>".$st_w."</td>";
		      $total_w_st = $st_w + $total_w_st;
		      echo "<td style='width:20px;'>".$ot_h."</td>";
		      $total_h_ot = $ot_h + $total_h_ot;
		      echo "<td style='width:20px;'>".$st_h."</td>";
		      $total_h_st = $st_h + $total_h_st;
		      echo "<td style='width:20px;'>".$ot_oh."</td>";
		      $total_sh_ot = $ot_oh + $total_sh_ot;
		      echo "<td style='width:20px;'>".$st_oh."</td>";
		      $total_sh_st = $st_oh + $total_sh_st;
		      echo "<td style='width:140px;font-size:11px;'>".$detail."</td>";
		      echo "<td style='width:60px;'>".$mang_in[$f]."</td>";
		      echo "<td style='width:60px;'>".$mang_out[$f]."</td>";
		      echo "</tr>";
		     $f=$f+1;
		}//het vong lap tao report
		/*      echo "<tr>";
		      echo "<td colspan = '3'>Total(hrs)</td>";
		      echo "<td>".$total_w_ot."</td>";
		      echo "<td>".$total_w_st."</td>";
		      echo "<td>".$total_h_ot."</td>";
		      echo "<td>".$total_h_st."</td>";
		      echo "<td>".$total_sh_ot."</td>";
		      echo "<td>".$total_sh_st."</td>";
		      echo "<td colspan = '3'>&nbsp</td>";
		      echo "</tr>";*/
	      echo "</table></br>";
	      /*
		  echo "<table width='50%' border=0 id='tb1'>";
			echo "<tr>";
			echo "<td><strong>Late-In and early out times this period</strong></td>";
			echo "<td>".($count_lately + $count_early)."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td><strong>Forgot in-out times this period</strong></td>";
			echo "<td>".$count_x."</td>";
			 echo "</tr>";
		  echo "</table></br>";
		  		echo "<table width='50%' border=0 id='tb1'>";
					echo "<tr>";
					echo "<td><strong>Status of working</strong></td>";
					echo "<td><strong>Days</strong></td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>O: Attendance</td>";
					echo "<td>".$count_o."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>X: Absent</td>";
					echo "<td>".$count_x."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>L: Leaves</td>";
					echo "<td>".$count_l."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>OH: Holiday</td>";
					echo "<td>".$count_h."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>SH: Other Special holiday</td>";
					echo "<td>".$count_sh."</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td>Total working days</td>";
					$total_working_day = ($count_o + $count_h + $count_l + $count_sh) - $count_x;
					echo "<td>".$total_working_day."</td>";
					echo "</tr>";
				echo "</table></br>";
			*/
}
/*Ham hien thi thoi gian cham cong tham khao*/
function att_view_main($id){
	    // check authen if it is already logined
    session_start();
	$username = $_SESSION['id'];
	if($username == 0){
			header("location:index.php");
		}
			include("config.php");
			$username = $id;
			echo "<table width='100%' border=0 id='tb1'>";
			echo "<tr bgcolor=#F0F7F7>";
			$c_date1 = date('Y-n-j');
			$wday2 = date("D",strtotime($c_date1));
			echo "<td width='10%'>".date('j/n/Y')."</td>";
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
	                if((hoursToMinutes('9:00') <= hoursToMinutes($out2))&&(hoursToMinutes($out2) <= hoursToMinutes('23:59'))){
		                   $outtime2 = $out2;
		                   }
			else{
				    $outtime2 = '0:00';
				  }
			if((hoursToMinutes('0:01') <= hoursToMinutes($in2))&&(hoursToMinutes($in2) <= hoursToMinutes('14:10'))){
				    $intime2 = $in2;
				  }
			else{
				    $intime2 = '0:00';
				 }
			echo "<td width='10%'>".$wday2."</td>";
			echo "<td width='20%'>".$intime2."</td>";
			echo "<td width='20%'>".$outtime2."</td>";
			echo "<td width='40%' rowspan='2'bgcolor='#A9BCF5'><b>REMARK</b></td>";
			echo "</tr>";
			echo "<tr bgcolor='#A9BCF5'>";
			echo "<td width='10%'><b>Date</b></td>";
			echo "<td width='10%'><b>Weekday</b></td>";
			echo "<td width='20%'><b>IN</b></td>";
			echo "<td width='20%'><b>OUT</b></td>";
            //echo "<td width='40%'>Comment</td>";
			echo "</tr>";
			$curr_month = date("n");
			$curr_year = date("Y");
			$last_year = date("Y")-1;
			$curr_day = date("j");
			if ($curr_month == 1){//neu la dau nam thi tinh lastmonth la cuoi thang
					$last_month = 12;
					$last_year = date("Y")-1;
					$days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year); 
					}
			else{
				$last_month = date("n")-1;
				$days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year); 
			    }
			$total_rows = $curr_day + $days_in_last_month;
			$mang_in = array();//mang chua du lieu in
			$mang_out = array();// mang chua du lieu out
			$q = 0; // chi muc cua mang
	for ($i=21;$i<=$total_rows;$i++)//gan gia tri cho mang
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
			where staff_id = '$username' and date(checktime) = '$c_date' order by checktime asc";
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
                $f = 0; // khoi tao chi muc mang
	for ($i=21;$i<=$total_rows;$i++)// in du lieu 
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
		        $sql_detail = "select detailwork from overtime where staff_id = '$username' and workday = '$c_date'";
			$sql_holiday = "select count(holiday) as kq,note from public_holiday where holiday = '$c_date' group by note";
			$sql_leaves = "select count(fromdate) as leaves,a_p,reason from leaves where staff_id = '$id' and fromdate <= '$c_date' and '$c_date' <= todate group by fromdate,a_p,reason";
			$result_detail = pg_query($connection, $sql_detail);
			$result_holiday = pg_query($connection, $sql_holiday);
			$row_detail = pg_fetch_object($result_detail);
			$row_holiday = pg_fetch_object($result_holiday);
			$result_leaves = pg_query($connection, $sql_leaves);
			$row_leaves = pg_fetch_object($result_leaves);
			$detail = $row_detail->detailwork;
			$ngaynghi = $row_holiday->kq;
			$note_holiday = $row_holiday->note;
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
			$wday = date("D",strtotime($c_date)); // doi ngay trong tuan sang thu
			if(hoursToMinutes($mang_in[$f]) == hoursToMinutes($mang_out[$f])){
				if(hoursToMinutes($mang_in[$q]) < hoursToMinutes('12:00')){
					$mang_out[$f] = '';
				}
				else{
					$mang_in[$f] = '';
				}
			}
			echo "<tr>";
			echo "<td width='10%'>".date("j/n/Y",strtotime($c_date))."</td>";
			echo "<td width='10%'>".$wday."</td>";
			echo "<td width='20%'>".$mang_in[$f]."</td>";
			echo "<td width='20%'>".$mang_out[$f]."</td>";
                        // thong bao quen check in out
			if(($mang_in[$f] == '')||($mang_out[$f] == '')){
				if($ngaynghi>0 ||($ketqua_nghiphep >= 1)){
					echo "<td width='40%'>".$note_holiday.'-'.$reason."</td>";
				}
				elseif(($wday != 'Sun')&&($c_date != date('Y-n-j'))){
						echo "<td width='40%' bgcolor='yellow'>Submit approved documents ASAP</td>";
						}
				else{
					echo "<td width='40%'>".$detail."</td>";
				}
			}
			else{
			    if(($ngaynghi > 0)||($ketqua_nghiphep >= 1)){
					echo "<td width='40%'>".$note_holiday.'-'.$reason."</td>";
				}
				else{
					echo "<td width='40%'>".$detail."</td>";
				}
			}
			echo "</tr>";
                        $f=$f+1;// tang so chi muc mang
		}
		echo "</table>";
}
?>

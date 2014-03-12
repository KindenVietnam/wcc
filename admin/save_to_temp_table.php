<?php
    include_once('ot.php');
    include_once('find_sat_in_month.php');
function save_to_temp($id, $month){
    include('config.php');
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
    $curr_month = $month;
    $last_year = date("Y")-1;
    $curr_year = date("Y");
    if($curr_month == 1){//nam truoc 
        $last_month = 12;
        $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year);
    }
    else{
        $last_month = $month - 1;
        $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year);
    }
    switch ($days_in_last_month){
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
    $sql_bang_cham_cong = "select count(staff_id) as chamcong from bang_cham_cong where staff_id = '$id' and date_part('month', date) = '$month' and date_part('year', date) = '$curr_year'";
    $sql_bang_tong_cham_cong = "select count(staff_id) as tong_chamcong from bang_tong_cham_cong where staff_id = '$id' and date_part('month', date) = '$month' and date_part('year', date) = '$curr_year'";
    $sql_bang_tong_overtime = "select count(staff_id) as tong_overtime from bang_tong_overtime where staff_id = '$id' and date_part('month', date) = '$month' and date_part('year', date) = '$curr_year'";
    $sql_count_early_lately = "select count(staff_id) as early_lately from count_early_lately where staff_id = '$id' and date_part('month', date) = '$month' and date_part('year', date) = '$curr_year'";
    // query du lieu cham cong
    $result_bang_cham_cong = pg_query($connection,$sql_bang_cham_cong);
    $row_bang_cham_cong = pg_fetch_array($result_bang_cham_cong);
    $ketqua_bang_cham_cong = $row_bang_cham_cong['chamcong'];
    // query du lieu tong cham cong
    $result_bang_tong_cham_cong = pg_query($connection,$sql_bang_tong_cham_cong);
    $row_bang_tong_cham_cong = pg_fetch_array($result_bang_tong_cham_cong);
    $ketqua_bang_tong_cham_cong = $row_bang_tong_cham_cong['tong_chamcong'];
    // query du lieu tong overtime
    $result_bang_tong_overtime = pg_query($connection,$sql_bang_tong_overtime);
    $row_bang_tong_overtime = pg_fetch_array($result_bang_tong_overtime);
    $ketqua_bang_tong_overtime = $row_bang_tong_overtime['tong_overtime'];
    // query du lieu count early lately
    $result_count_early_lately = pg_query($connection,$sql_count_early_lately);
    $row_count_early_lately = pg_fetch_array($result_count_early_lately);
    $ketqua_count_early_lately = $row_count_early_lately['early_lately'];
    // bat dau vao phan tinh toan
   if(($ketqua_bang_cham_cong > 0)&&($ketqua_bang_tong_cham_cong > 0)&&($ketqua_bang_tong_overtime>0)&&($ketqua_count_early_lately)){
	$update = 1;// update du lieu
    }
   else{
	$update = 0; // insert du lieu
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
		    // du du lieu vao bang cham cong
		      $ngay = date("D",strtotime($date));
		      $total_w_ot = $ot_w + $total_w_ot;
		      $total_w_st = $st_w + $total_w_st;
		      $total_h_ot = $ot_h + $total_h_ot;
		      $total_h_st = $st_h + $total_h_st;
		      $total_sh_ot = $ot_oh + $total_sh_ot;
		      $total_sh_st = $st_oh + $total_sh_st;
		if($update == 0){
		    $sql_insert_bang_cham_cong = "insert into bang_cham_cong(staff_id,date,weekday,att_mark,w_ot,w_st,h_ot,h_st,sh_ot,sh_st,detail_work,in_time,out_time)
			    values('$id','$c_date','$ngay','$att_mark','$ot_w','$st_w','$ot_h','$st_h','$ot_oh','$st_oh','$detail','$mang_in[$f]','$mang_out[$f]')";
		    pg_query($connection,$sql_insert_bang_cham_cong);
		}
		else{
		    $sql_update_bang_cham_cong = "update bang_cham_cong
		    set att_mark = '$att_mark',w_ot='$ot_w',w_st='$st_w',h_ot='$ot_h',h_st='$st_h',sh_ot='$ot_oh',sh_st='$st_oh',detail_work='$detail',in_time='$mang_in[$f]',out_time='$mang_out[$f]' where staff_id='$id' and date = '$c_date'";
		    pg_query($connection,$sql_update_bang_cham_cong);
		}
	    $f=$f+1;
	}//het vong lap tao report
	$ngay_chot = date("Y-$month-26");
	$total_workingday = ($count_o + $count_l + $count_h + $count_sh) - $count_x;
	if($update == 0){
	    //insert du lieu
	    $sql_insert_bang_tong_overtime = "INSERT INTO bang_tong_overtime(staff_id, date, t_w_ot, t_w_st, t_h_ot, t_h_st, t_sh_ot, t_sh_st)
				VALUES ('$id', '$ngay_chot', '$total_w_ot', '$total_w_st', '$total_h_ot', '$total_h_st', '$total_sh_ot', '$total_sh_st');";
	    $sql_insert_bang_tong_cham_cong = "INSERT INTO bang_tong_cham_cong(staff_id, date, t_att, t_absent, t_leaves, t_oh, t_sh, t_workingday)
						VALUES ('$id', '$ngay_chot', '$count_o', '$count_x', '$count_l', '$count_h', '$count_sh', '$total_workingday');";
	    $sql_insert_count_early_lately = "INSERT INTO count_early_lately(staff_id, date, early, lately)
						VALUES ('$id', '$ngay_chot', '$count_early', '$count_lately');";
	    pg_query($connection, $sql_insert_bang_tong_cham_cong);
	    pg_query($connection, $sql_insert_bang_tong_overtime);
	    pg_query($connection, $sql_insert_count_early_lately);
	}
	else{
	    //update du lieu
	    $sql_update_bang_tong_overtime = "UPDATE bang_tong_overtime
					      SET t_w_ot='$total_w_ot', t_w_st='$total_w_st', t_h_ot='$total_h_ot', t_h_st='$total_h_st', t_sh_ot='$total_sh_ot', t_sh_st='$total_sh_st'
					      WHERE staff_id = '$id' and date = '$ngay_chot';";
	    $sql_update_bang_tong_cham_cong = "UPDATE bang_tong_cham_cong
					       SET t_att='$count_o', t_absent='$count_x', t_leaves='$count_l', t_oh='$count_h', t_sh='$count_sh', t_workingday='$total_workingday'
					       WHERE staff_id = '$id' and date = '$ngay_chot';";
	    $sql_update_count_early_lately = "UPDATE count_early_lately
					      SET early='$count_early', lately='$count_lately'
					      WHERE staff_id='$id' and date = '$ngay_chot';";
	    pg_query($connection, $sql_update_bang_tong_overtime);
	    pg_query($connection, $sql_update_bang_tong_cham_cong);
	    pg_query($connection, $sql_update_count_early_lately);
	}
	echo ".";
}
?>
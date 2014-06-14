<link rel="stylesheet" type="text/css" href="table.css"/>
<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<script src="checkall_js.js" language="javascript"></script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Over Time for group</title>
</head>
<body>
    <?php
            session_start();
            $manhanvien = $_SESSION['id'];
            if($manhanvien == 0){
                    header("location:index.php");
            }
            include('config.php');
            $date = $_POST['date'];
    ?>
<h3>Over Time For All Staffs</h3>
<form id="form1" name="form_overtime" method="post" action="welcome.php?4">
  <!--<label>Location
  <select name="select_location">
    <?php
           // $sql_load_location = "select * from machine where status <> '0'";
            //$result_load_location = pg_query($connection, $sql_load_location);
            //echo "<option>All Staff</option>";
          //  while($row_load_location = pg_fetch_array($result_load_location))
	//			{
	//				echo "<option>".$row_load_location['name']."</option>";
	//			}
    ?>
  </select>
  </label>-->
  <label>Date
  <input type="text" name="date" onFocus="JavaScript:showCalendarControl(this);" value="<?php echo $date; ?>"/>
  </label>
  <input type="submit" name="Submit" value="Search" />
  <p> &nbsp </p>
  <p><input type="checkbox" name="status_ah" value="1">AH</p>
  <table width="100%" border="0" id = "tb1">
    <tr>
      <!--<td><b>Number</b></td>
      <td><b>Location</b></td>-->
      <td><b>Staff ID</b></td>
      <td><b>Name</b></td>
      <!--<td><b>Date</b></td>-->
      <td><b><a href="javascript:void();" onClick="javascript:checkAll('form_overtime', true);">check all</a> | 
            <a href="javascript:void();" onClick="javascript:checkAll('form_overtime', false);">uncheck all</a></b>
      </td>
    </tr>
  <?php
   // $machine_name = $_POST['select_location'];
	//if(strcmp($machine_name,"All Staff")==0){
	//	$sql_load_staff = "select staff_id as staffid,name as staff_name from staff where staff_id >= '1000' and staff_id <= '1999'order by staffid";
	//}
   // else{
    $sql_load_staff = "select * from staff order by staff_id asc";
	//}
	
    $result_load_staff = pg_query($connection,$sql_load_staff);
    $id = 0;
    while($row_load_staff = pg_fetch_array($result_load_staff))
		{
                    $id=$id+1;
                    echo '<tr>';
                    //echo '<td>'.$id.'</td>';
                    //echo '<td>'.$machine_name.'</td>';
                    echo '<td>'.$row_load_staff['staff_id'].'</td>';
                    echo '<td>'.$row_load_staff['name'].'</td>';
                    //echo '<td><input type="hidden" name="date" value="'.$date.'"></td>';
                    echo '<td><label><input type="checkbox" name="checklist[]" value="'.$row_load_staff['staffid'].'" id="check_box_id"/>Over time</label></td>';
                    echo '</tr>';
		}
		echo "<tr>";
		echo "<td colspan='2'>Total staffs</td>";
		echo "<td>".$id."</td>";
		echo "</tr>";
  ?>
  </table>
  <p>
    <label>
    <input type="submit" name="approved" value="Approve" />
    </label>
    <label>
    <input type="submit" name="cancel" value="Cancel" />
    </label>
  </p>
</form>
<p>&nbsp;</p>
<?php
include_once('ot.php');
$date = $_POST['date'];
$ah_status = $_POST['status_ah'];
if(isset($_POST['approved']))
{
      echo "<table id = 'tb1' width = '100%'>";
      echo "<tr>";
      echo "<td colspan='1' rowspan=3>Staff ID</td>";
      echo "<td colspan='1' rowspan=3 style='width: 170px;'>Name</td>";
      echo "<td colspan='1' rowspan=3>Workday</td>";
      echo "<td colspan='1' rowspan=3>Weekday</td>";
      echo "<td colspan='6'>Over Time</td>";
      echo "<td colspan=2>Working Time</td>";
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
      foreach($_POST['checklist'] as $check_id){
	  if(!empty($_POST['checklist'])){
	    //them function tim thoi gian in out
	    //kiem tra va tinh overtime
	    //luu vao bang overtime
	      tinh_inout($ah_status,$check_id,$date,$trangthai);
	  }
	}
   echo "<script>alert('Successfull !')</script>";
   echo "</table>";
}
function tinh_inout($ah_status,$staff_id, $workday,$trangthai){
            include('config.php');
            $username = $staff_id;
            $sql_holiday = "select count(holiday) as ngayle from public_holiday where holiday = '$workday'";
            $sql_staff_name = "select name from staff where staff_id = '$username'";
            $ketqua_holiday = pg_query($connection, $sql_holiday);
            $row_holiday = pg_fetch_array($ketqua_holiday);
            $ketqua_staff_name = pg_query($connection, $sql_staff_name);
            $row_staff_name = pg_fetch_array($ketqua_staff_name);
            $staff_name = $row_staff_name['name'];
            $holiday_status = $row_holiday['ngayle'];// lay ngay nghi le trong co so du lieu
			$curr_month = date("n", strtotime($workday));
			$curr_year = date("Y", strtotime($workday));
			$last_year = date("Y", strtotime($workday))-1;
			$curr_day = date("j", strtotime($workday));
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
			if(hoursToMinutes($mang_in[$f]) == hoursToMinutes($mang_out[$f])){
				if(hoursToMinutes($mang_in[$f]) < hoursToMinutes('12:00')){
					$mang_out[$f] = '';
				}
				else{
					$mang_in[$f] = '';
				}
			}
                        if(strtotime($workday) == strtotime($c_date)){
                            $ngaytrongtuan = date('D', strtotime($c_date));
                             overtime_from_location($ah_status,$staff_id,$staff_name,$ngaytrongtuan,$c_date,$mang_in[$f],$mang_out[$f],$holiday_status,$trangthai);
                        }
                        $f=$f+1;// tang so chi muc mang
		}
}

// ham xuat du lieu overtime

function overtime_from_location($ah_status,$staffid,$ten_staff,$weekday,$date,$thoigianvao,$thoigianra,$holiday,$trangthai) {
      $ot_gio_ra = 0;
      $ot_gio_vao = 0;
      $ot_phut_ra = 0;
      $ot_phut_vao = 0;
      $st_gio_ra = 0;
      $st_gio_vao = 0;
      $st_phut_vao = 0;
      $st_phut_ra = 0;
      $total_ot_vao = 0;
      $total_st_vao = 0;
      $total_ot_ra = 0;
      $total_st_ra = 0;
      $total_ot = 0;
      $total_st = 0;
      $intime =  $thoigianvao;
      $outtime = $thoigianra;
      $giovao = split(':',$intime);
      $giora = split(':',$outtime);
      $gio_vao = $giovao[0];// lay phut tu gio vao lam viec
      $phut_vao = $giovao[1];// lay giay tu gio vao lam viec
      $gio_ra = $giora[0];// lay phut tu gio ra ve
      $phut_ra = $giora[1];// lay giay tu gio ra ve
      /* Tinh over time gio vao */
      if (($gio_vao < 8)&&($gio_vao>=6)) {// over time gio_vao OT
          if ($phut_vao != 0) {
               $ot_gio_vao = 8-($gio_vao + 1);
          }
          else{
               $ot_gio_vao = 8 - $gio_vao;
          }
      }
      else if ($gio_vao < 6) { // Tinh ST gio vao
          if ($phut_vao != 0) {
               $st_gio_vao = 8-($gio_vao + 3);
               $ot_gio_vao = 2;
          }
          else{
               $st_gio_vao = 8-($gio_vao + 2);
               $ot_gio_vao = 2;
          }
      }
      else{
          $ot_gio_vao = 0;
          $st_gio_vao = 0;
      }
      /*Tinh over time phut vao*/
      if ($st_gio_vao > 0) {
          $st_phut_vao = overtime_phut_vao($phut_vao,$gio_vao);
          $ot_phut_vao = 0;
      }
      else if (($st_gio_vao == 0)&&($ot_gio_vao == 2)) {
          $st_phut_vao = overtime_phut_vao($phut_vao,$gio_vao);
          $ot_phut_vao = 0;
      }
      else{
          $ot_phut_vao = overtime_phut_vao($phut_vao,$gio_vao);
      }
      /*Tinh tong ot va st cua check in*/
      $total_ot_vao = $ot_gio_vao + $ot_phut_vao;
      $total_st_vao = $st_gio_vao + $st_phut_vao;
      /*Tinh over time gio ra*/
      if (($gio_ra >= 17)&&($gio_ra <= 22)) {
          $ot_gio_ra = $gio_ra - 17;
      }
      else if ($gio_ra > 22) {
          $st_gio_ra = $gio_ra - 22;
          $ot_gio_ra = 5;
      }
      else{
          $st_gio_ra = 0;
          $ot_gio_ra = 0;
      }
      /*Tinh over time phut ra*/
      if ($st_gio_ra > 0) {
          $st_phut_ra = overtime_phut_ra($phut_ra,$gio_ra);
          $ot_phut_ra = 0;
      }
      else if (($st_gio_ra == 0)&&($ot_gio_ra == 5)) {
          $st_phut_ra = $overtime_phut_ra($phut_ra,$gio_ra);
          $ot_phut_ra = 0;
      }
      else{
          $ot_phut_ra = overtime_phut_ra($phut_ra,$gio_ra);
          $st_phut_ra = 0;
      }
      /*Tinh tong ot va st check out*/
      $total_ot_ra = $ot_gio_ra + $ot_phut_ra;
      $total_st_ra = $st_gio_ra + $st_phut_ra;
      /*Tinh tong over vao va ra*/
      $total_ot = $total_ot_ra + $total_ot_vao;
      $total_st = $total_st_ra + $total_st_vao;
      $tong_ot_ra = $total_ot_ra;
      $tong_st_ra = $total_st_ra;
      $tong_ot_vao = $total_ot_vao;
      $tong_st_vao = $total_st_vao;
      $tong_ot = $total_ot;
      $tong_st = $total_st;
      $hour_in = $gio_vao;
      $hour_out = $gio_ra;
      $minutes_vao = $phut_vao;
      overtime_output($ah_status,$trangthai,$tong_ot_ra,$tong_st_ra,$tong_ot_vao,$tong_st_vao,$tong_ot,$tong_st,$hour_in,$hour_out,$minutes_vao,$weekday,$date,$staffid,$holiday,$thoigianvao,$thoigianra,$ten_staff);
     // alert(trangthai + tong_ot_ra + tong_st_ra + tong_ot_vao + tong_st_vao + tong_ot + tong_st + hour_in + hour_out + minutes_vao + date + staffid + holiday);
}
function overtime_output($ah_status,$status,$total_ot_ra,$total_st_ra,$total_ot_vao,$total_st_vao,$total_ot,$total_st,$gio_vao,$gio_ra,$phut_vao,$weekday,$ngay,$manv,$ngaynghi,$timein,$timeout,$tennhanvien){
     //var weekday = ngay.getDay();
     //$weekday = $ngay;
     $total_ot_h = 0;
     $total_st_h = 0;
     $total_ot_oh = 0;
     $total_st_oh = 0;
     $h = 0;
     $oh = 0;
     if ((($weekday == 'Sun')||($weekday == 'Sat'))&&($ah_status == 1)) {//hien over time cua ca ngay t7 va chu nhat
          if (($gio_ra <= 17)&&($gio_vao >= 8)) {
                    if (($gio_ra > 12)&&($gio_vao < 12)) {
                         if ($phut_vao != 0) {
                              $h = $gio_ra - ($gio_vao + 2); 
                         }
                         else{
                              $h = $gio_ra - ($gio_vao + 1);
                          }
                    }
                    else{
                         if ($phut_vao != 0) {
                              $h = $gio_ra - ($gio_vao + 1); 
                         }
                         else{
                              $h = $gio_ra - $gio_vao;
                         }
                    }
               }
          else if (($gio_ra <= 17)&&($gio_vao < 8)) {
                    if (($gio_ra > 12)&&($gio_vao < 12)) {
                         if ($phut_vao != 0) {
                              $h = $gio_ra - 9; 
                         }
                         else{
                              $h = $gio_ra - 8;
                          }
                    }
                    else{
                         if ($phut_vao != 0) {
                              $h = $gio_ra - 8; 
                         }
                         else{
                              $h = $gio_ra - 7;
                         }
                    }
               }
          else if (($gio_ra > 17)&&($gio_vao >=8)) {
                    if (($gio_ra > 12)&&($gio_vao < 12)) {
                         if ($phut_vao != 0){
                              $h = 17 - ($gio_vao + 2); 
                         }
                         else{
                              $h = 17 - ($gio_vao + 1);
                          }
                    }
                    else{
                         if ($phut_vao != 0){
                              $h = 17 - ($gio_vao + 1); 
                         }
                         else{
                              $h = 17 - $gio_vao;
                         }
                    }
               }
          else{
                   $h = 8;
               }
          $total_ot_h = $h+$total_ot;
          $total_st_h = $total_st;
          if($weekday == 'Sat'){
            if($total_ot_h <= 4){
                $att = '1/2AH';
            }
            else{
                $att = 'AH';
            }
          }
          else{
               $att = 'H';
          }
          if($total_ot_h <= 4){
            
          }
          overtime_to_db($manv,$ngay,$weekday,$att,'','',$total_ot_h,$total_st_h,'','','',$timein,$timeout,$tennhanvien);
     }
      /*Doi voi ngay SH lam tuong tu nhu thu 7 va chu nhat*/
     else if ($ngaynghi == 1) {
          if (($gio_ra <= 17)&&($gio_vao >= 8)) {
                    if (($gio_ra > 12)&&($gio_vao < 12)) {
                         if ($phut_vao != 0) {
                              $oh = $gio_ra - ($gio_vao + 2); 
                         }
                         else{
                              $oh = $gio_ra - ($gio_vao + 1);
                          }
                    }
                    else{
                         if ($phut_vao != 0) {
                              $oh = $gio_ra - ($gio_vao + 1); 
                         }
                         else{
                              $oh = $gio_ra - $gio_vao;
                         }
                    }
               }
          else if (($gio_ra <= 17)&&($gio_vao < 8)) {
                    if (($gio_ra > 12)&&($gio_vao < 12)) {
                         if ($phut_vao != 0) {
                              $oh = $gio_ra - 9; 
                         }
                         else{
                              $oh = $gio_ra - 8;
                          }
                    }
                    else{
                         if ($phut_vao != 0) {
                              $oh = $gio_ra - 8; 
                         }
                         else{
                              $oh = $gio_ra - 7;
                         }
                    }
               }
          else if (($gio_ra > 17)&&($gio_vao >=8)) {
                    if (($gio_ra > 12)&&($gio_vao < 12)) {
                         if ($phut_vao != 0){
                             $oh = 17 - ($gio_vao + 2); 
                         }
                         else{
                              $oh = 17 - ($gio_vao + 1);
                          }
                    }
                    else{
                         if ($phut_vao != 0){
                              $oh = 17 - ($gio_vao + 1); 
                         }
                         else{
                              $oh = 17 - $gio_vao;
                         }
                    }
               }
          else{
                   $oh = 8;
               }
          $total_ot_oh = $oh+$total_ot;
          $total_st_oh = $total_st;
          overtime_to_db($manv,$ngay,$weekday,'SH','','','','',$total_ot_oh,$total_st_oh,'',$timein,$timeout,$tennhanvien);
          
     }
     else{ // hien over time ngay thuong
                   overtime_to_db($manv,$ngay,$weekday,'O',$total_ot,$total_st,'','','','','',$timein,$timeout,$tennhanvien);
         }
          
}
//ham tinh overtime tu form dang ki overtime theo vung(nhom)
function overtime_phut_ra($time_out,$gio_out) { // function to compute over time in minutes for checking out
     if ($gio_out >= 17) {
               if (($time_out >= 15)&&($time_out < 30)) {
                    $phut_out = 0.25;
                    return $phut_out;
               }
               else if (($time_out >=30)&&($time_out < 45 )) {
                     $phut_out = 0.5;
                    return $phut_out;
               }
               else if (($time_out >= 45 )&&($time_out < 60 )) {
                    $phut_out = 0.75;
                    return $phut_out;
               }
               else{
                    $phut_out = 0;
                    return $phut_out;
               }
     }
     else{
          return 0;
     }
}
function overtime_phut_vao($time_in,$gio_in) { //function to compute over time in minutes for checking in
   //  if (gio_in < 8) {
               if (($time_in <= 45)&&($time_in > 30)) {
                    $phut_in = 0.25;
                    return $phut_in;
               }
               else if (($time_in <= 30)&&($time_in > 15 )) {
                    $phut_in = 0.5;
                    return $phut_in;
               }
               else if (($time_in <= 15 )&&($time_in > 0 )) {
                    $phut_in = 0.75;
                    return $phut_in;
               }
               else{
                    $phut_in = 0;
                    return $phut_in;
               }
}

function overtime_to_db($staffid,$work_day,$week_day,$attmark,$w_ot,$w_st,$h_ot,$h_st,$p_ot,$p_st,$detail_work,$thoigianvao,$thoigianra,$staffname){
    include("config.php");
     //code
     //echo $staffid . $work_day . $week_day . $attmark . $w_ot . $w_st . $h_ot . $h_st . $p_ot . $p_st . $detail_work . "</br>";
    $sql_insert_overtime = "INSERT INTO overtime(staff_id,workday,weekday,att_mark,wot,wst,hot,hst,pot,pst,detailwork) VALUES ('$staffid','$work_day','$week_day','$attmark','$w_ot','$w_st','$h_ot','$h_st','$p_ot','$p_st','$detail_work')";
    $ketqua = pg_query($connection, $sql_insert_overtime);
    // neu la thu bay thi luu vao bang tb_ah
    if(strcmp(trim($week_day),'Sat')==0){
      $sql_insert_ah = "INSERT INTO tb_ah(staff_id,workday,ah_status) VALUES('$staffid','$work_day','AH')";
      pg_query($connection, $sql_insert_ah);
    }
    if(!$ketqua){
      echo "<script>alert('Saving Error !')</script>";
    }
    else{
        echo "<tr>";
        echo "<td style='width: 70px;'>".$staffid."</td>";
        echo "<td style='width: 170px;'>".$staffname."</td>";
	echo "<td style='width: 70px'>".$work_day."</td>";
        echo "<td style='width: 70px'>".$week_day."</td>";
	//echo "<td>".$attmark."</td>";
	echo "<td>".$w_ot."</td>";
	echo "<td>".$w_st."</td>";
	echo "<td>".$h_ot."</td>";
	echo "<td>".$h_st."</td>";
	echo "<td>".$p_ot."</td>";
	echo "<td>".$p_st."</td>";
	echo "<td>".$thoigianvao."</td>";
	echo "<td>".$thoigianra."</td>";
	echo "</tr>";
    }
}
?>
</body>
</html>

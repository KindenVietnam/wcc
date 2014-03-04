<link rel="stylesheet" type="text/css" href="report.css"/>
<link rel="stylesheet" type="text/css" href="table.css"/>
	<?php
		$month = $_GET['thang'];
		$manhanvien = $_GET['id'];
	?> 
   <table width='100%' border=0>
    <td>
    <h2>Attendance Record</h2>
    </td>
    <td>
    <h2><?php echo date("$month-Y"); ?></h2>
    </td>
    <?php $last_m = $month-1; ?>
    <td>
    <h4>From <?php echo date("21-$last_m")." to ".date("20-$month-Y"); ?></h4>
    </td>
    </table>
    </br>
    <strong>Applicant&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<?php echo $manhanvien; ?></strong>
    <?php
		include_once('config.php');
		$khoang_1 = date("Y-$last_m-21");
		$khoang_2 = date("Y-$month-20");
		$curr_year = date("Y");
		$sql_ten_nhan_vien = "select name from staff where staff_id = '$manhanvien'";
		$result_ten_nhanvien = pg_query($connection,$sql_ten_nhan_vien);
		$row_ten_nhan_vien = pg_fetch_array($result_ten_nhanvien);
    ?>
    <strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo $row_ten_nhan_vien['name']; ?></strong>
    <strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspApplicant's Signature</strong>
    <table width='100%' border=0 id='report'>
		<tr>
			<td colspan='2' rowspan=3>Date</td>
			<td colspan='1' rowspan=3 style='width:70px;'>Attendance Mark</td>
			<td colspan='6'>Over Time</td>
			<td rowspan='3'>Detail Work</td>
			<td colspan=2'>Working Time</td>
		</tr>
		<tr>
			<td colspan=2>Weekday</td>
			<td colspan=2>Holiday</td>
			<td colspan=2>Public Holiday</td>
			<td rowspan=2 style='width:80px'>IN</td>
			<td rowspan=2 style='width:80px'>OUT</td>
		</tr>
		<tr>
			<td style='width:50px'>OT</td>
			<td style='width:50px'>ST</td>
			<td style='width:50px'>OT</td>
			<td style='width:50px'>ST</td>
			<td style='width:50px'>OT</td>
			<td style='width:50px'>ST</td>
		</tr>
		<?php
			// truy van du lieu tu bang tam tai day
			$sql_select_bang_cham_cong = "select * from bang_cham_cong where staff_id = '$manhanvien' and date >= '$khoang_1' and date <= '$khoang_2' order by date asc";
			
			$result_bang_cham_cong = pg_query($connection, $sql_select_bang_cham_cong);
			while($rows = pg_fetch_array($result_bang_cham_cong)){
		?>
		<tr>
			<td style='width: 70px;'><?php echo $rows['date']; ?></td>
			<td style='width: 70px'><?php echo $rows['weekday']; ?></td>
			<td><?php echo $rows['att_mark']; ?></td>
			<td><?php echo $rows['w_ot'];?></td>
			<td><?php echo $rows['w_st'];?></td>
			<td><?php echo $rows['h_ot'];?></td>
			<td><?php echo $rows['h_st'];?></td>
			<td><?php echo $rows['sh_ot'];?></td>
			<td><?php echo $rows['sh_st'];?></td>
			<td style='width:180px'><?php echo $rows['detail_work'];?></td>
			<td><?php echo $rows['in_time'];?></td>
			<td><?php echo $rows['out_time'];?></td>
		</tr>
		<?php } ?>
		<tr>
		<?php
			$sql_select_bang_tong_overtime = "select * from bang_tong_overtime where staff_id = '$manhanvien' and date_part('year',date) = '$curr_year' and date_part('month',date)='$month'";
			$result_bang_tong_overtime = pg_query($connection, $sql_select_bang_tong_overtime);
			$row_bang_tong_cham_cong = pg_fetch_array($result_bang_tong_overtime);
		?>
			<td colspan = '3'>Total(hrs)</td>
			<td><?php echo $row_bang_tong_cham_cong['t_w_ot'];?></td>
			<td><?php echo $row_bang_tong_cham_cong['t_w_st'];?></td>
			<td><?php echo $row_bang_tong_cham_cong['t_h_ot'];?></td>
			<td><?php echo $row_bang_tong_cham_cong['t_h_st'];?></td>
			<td><?php echo $row_bang_tong_cham_cong['t_sh_ot'];?></td>
			<td><?php echo $row_bang_tong_cham_cong['t_sh_st'];?></td>
			<td colspan = '3'>&nbsp</td>
		</tr>
    </table>
    </br>
    <?php
	$sql_select_count_early_lately = "select * from count_early_lately where staff_id = '$manhanvien' and date_part('year',date) = '$curr_year' and date_part('month',date)='$month'";
	$result_count_early_lately = pg_query($connection, $sql_select_count_early_lately);
	$row_count_early_lately = pg_fetch_array($result_count_early_lately);
    ?>
	<table width='50%' border=0 id='tb1'>
		<tr>
			<td><strong>Late-In Times this period</strong></td>
			<td><?php echo $row_count_early_lately['early'];?></td>
		</tr>
		<tr>
			<td><strong>Early-out Times this period</strong></td>
			<td><?php echo $row_count_early_lately['lately'];?></td>
		</tr>
	</table>
	</br>
	<?php
		$sql_select_bang_tong_cham_cong = "select * from bang_tong_cham_cong where staff_id = '$manhanvien' and date_part('year',date) = '$curr_year' and date_part('month',date)='$month'";
		$result_bang_tong_cham_cong = pg_query($connection, $sql_select_bang_tong_cham_cong);
		$row_bang_tong_cham_cong = pg_fetch_array($result_bang_tong_cham_cong);
	?>
	<table width='50%' border=0 id='tb1'>
		<tr>
			<td><strong>Status of working</strong></td>
			<td><strong>Days</strong></td>
		</tr>
		<tr>
			<td>O: Attendance</td>
			<td><?php echo $row_bang_tong_cham_cong['t_att'];?></td>
		</tr>
		<tr>
			<td>X: Absent</td>
			<td><?php echo $row_bang_tong_cham_cong['t_absent'];?></td>
		</tr>
		<tr>
			<td>L: Leaves</td>
			<td><?php echo $row_bang_tong_cham_cong['t_leaves'];?></td>
		</tr>
		<tr>
			<td>OH: Holiday</td>
			<td><?php echo $row_bang_tong_cham_cong['t_oh'];?></td>
		</tr>
		<tr>
			<td>SH: Other Special holiday</td>
			<td><?php echo $row_bang_tong_cham_cong['t_sh'];?></td>
		</tr>
		<tr>
			<td>Total working days</td>
			<td><?php echo $row_bang_tong_cham_cong['t_workingday'];?></td>
		</tr>
	</table>
</br>
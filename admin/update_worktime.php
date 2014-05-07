<link rel="stylesheet" type="text/css" href="table.css"/>
<script src="cal_overtime.js" language="javascript"></script> 
<b>Update Working Time</b></br></br>
<?php
include_once('config.php');
include('ot.php');
$staffid = $_GET['id'];
$name = $_GET['name'];
$ngay = $_GET['day'];
$intime = $_GET['in'];
$outtime = $_GET['out'];
$month = $_GET['month'];
$sql_holiday = "select count(holiday) as ngayle from public_holiday where holiday = '$ngay'";
$sql_overtime = "select * from overtime where staff_id = '$staffid' and workday = '$ngay'";
$sql_status = "select count(staff_id) as status from overtime where staff_id = '$staffid' and workday = '$ngay'";
$result_overtime = pg_query($connection, $sql_overtime);
$ketqua_holiday = pg_query($connection, $sql_holiday);
$ketqua_status = pg_query($connection, $sql_status);
$row_holiday = pg_fetch_array($ketqua_holiday);
$row_overtime = pg_fetch_array($result_overtime);
$row_status = pg_fetch_array($ketqua_status);
$holiday_status = $row_holiday['ngayle'];// lay ngay nghi le trong co so du lieu
  $att_mark = $row_overtime['att_mark'];
  $wot = $row_overtime['wot'];
  $wst = $row_overtime['wst'];
  $hot = $row_overtime['hot'];
  $hst = $row_overtime['hst'];
  $pot = $row_overtime['pot'];
  $pst = $row_overtime['pst'];
  $detailwork = $row_overtime['detailwork'];
  $status_update_detele = $row_status['status'];
?>
<form id="form1" name="update_worktime" method="post" action="update_overtime_leaves_inout.php">
<label>Staff ID : </label><input type='text' name='staffid' value='<?php echo $staffid; ?>' readonly = '1'></br>
<label>Staff Name : </label><input type='text' name='staffname' value='<?php echo $name; ?>' readonly = '1'></br>
<input type = 'hidden' name='ngayle' value='<?php echo $holiday_status; ?>'>
<input type = 'hidden' name='thang' value='<?php echo $month; ?>'>
<input type = 'hidden' name='status_update' value='<?php echo $status_update_detele; ?>'>
<table border=0 id='mtb'>
<tr>
<td colspan='2' rowspan=3>Date</td>
<td colspan='1' rowspan=3 style='width:70px;'>Attendance Mark</td>
<td colspan='6'>Over Time</td>
<td rowspan='3'>Detail Work</td>
<td colspan='2'>Working Time</td>
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
<tr>
<td style='width: 70px;'><input type = 'text' name = 'ngay' value="<?php echo $ngay; ?>" style='width: 70px;' readonly = '1' ></td>
<td style='width: 70px'><input type = "text" name = 'weekday' value = "<?php echo date("D",strtotime($ngay));?>" readonly = '1' style='width: 70px'></td>
<td><input type = 'text' name='att_mark' value="<?php echo $att_mark; ?>" style='width: 70px;'></td>
<td><input type = 'text' name ='ot_w' value="<?php echo $wot; ?>" style='width: 70px;'></td>
<td><input type = 'text' name = 'st_w' value = "<?php echo $wst; ?>" style='width: 70px;'></td>
<td><input type = 'text' name = 'ot_h' value = "<?php echo $hot; ?>" style='width: 70px;'></td>
<td><input type = 'text' name = 'st_h' value = "<?php echo $hst; ?>" style='width: 70px;'></td>
<td><input type = 'text' name = 'ot_oh' value = "<?php echo $pot; ?>" style='width: 70px;'></td>
<td><input type = 'text' name = 'st_oh' value="<?php echo $pst; ?>" style='width: 70px;'></td>
<td><input type = "text" name = 'detail' value = "<?php echo $detailwork; ?>" style='width: 200px;'></td>
<td><input type ='text' name='intime' value="<?php echo blank_space($intime);?>" style='width:80px'></td>
<td><input type ='text' name='outtime' value="<?php echo blank_space($outtime); ?>" style='width:80px'></td>
</tr>
</table>
<p>
    <label>Enter leave : </label>
<label>
<a href = "leaves.php?linkback=1&id=<?php echo $staffid; ?>&name=<?php echo $name; ?>&ngay=<?php echo $ngay;?>&month=<?php echo $month?>">
Leaves</a>
</label>
<p></br>
    <label>Business Trip : </label>
<label>
<input type="radio" name="radio" id="radio3" value="b" onclick = "congtac()"/>
Bussiness
</label>&nbsp&nbsp&nbsp&nbsp<p></br>
    <label>Over time AH : </label>
<label>
<input type="checkbox" id="check_ah" />AH (choose this option if this Saturday is AH)
</label>
<p>
</br>
<label>Calculate over time : </label>
<label>
<input type="radio" name="radio" id="radio4" value="o" onclick = "overtime(2)"/>
Over Time In
</label>
<label>
<input type="radio" name="radio" id="radio4" value="o" onclick = "overtime(1)"/>
Over Time Out
</label>
<label>
<input type="radio" name="radio" id="radio4" value="o" onclick = "overtime(3)"/>
Over Time In and Out
</label>
&nbsp&nbsp&nbsp&nbsp<p></br>
    <label> Assign Flag : </label>
<input type="radio" name="radio" id="radio5" value="fi" />
<label>
Forgot In(assign forgot check in)
</label>
<input type="radio" name="radio" id="radio5" value="fo" />
<label>
Forgot Out(assign forgot check out)
</label>
</p>
<p></br>
    <label> Input in-Out : </label>
<input type="radio" name="radio" id="radio5" value="fi" />
<label>
Other In(input check in)
</label>
<input type="radio" name="radio" id="radio5" value="fo" />
<label>
Other Out(input check out)
</label>
<!--<label>Reason
<textarea name="reason" id="textarea" cols="45" rows="5"></textarea>
</label>
</p>-->
<p>
<input type="submit" name="save" id="button" value="Save" />
</p>
</form>
<?php
     if($status_update_detele > 0){
          echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='update_overtime_leaves_inout.php?del=1&staffid=$staffid&name=$name&workday=$ngay&thang=$month';}\"><button>Detele overtime</button></a>";
     }
?>
<a href='search_att.php?id=<?php echo $staffid; ?>&name=<?php echo $name; ?>&month=<?php echo $month; ?>'><button>Cancel</button></a>


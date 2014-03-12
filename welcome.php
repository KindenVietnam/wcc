<html>
<head>
<title>ATTENDANCE RECORD ONLINE</title>
<link rel="stylesheet" type="text/css" href="css_vertical_menu.css"/>
<link rel="stylesheet" type="text/css" href="clock_calendar.css"/>
<link rel="stylesheet" type="text/css" href="table.css"/>
</head>
<body>
<table border = "0" width="100%">
<tr width = "100%" bgcolor='#C7E2E2'>
<td>
<img src="image/logo.gif" width="200" height="100">
</td>
<td>
<font color = "green">
<h2>
ATTENDANCE RECORD ONLINE (CTRL + F5 TO REFRESH)
</h2>
</font>
	<!--<em><p>Vì lý do kĩ thuật nên dữ liệu chấm công từ chiều 2/4/2013 đến sáng 3/4/2013 không có.</p>
	<p>Rất mong mọi người thông cảm và nộp bản bổ xung chấm công cho phòng hành chính.</p>
	<p>Xin chân thành cảm ơn .</p></em>-->

</td>
</tr>
<tr width ="100%">
<td width = "20%" valign ="top">
<ul id="css_vertical_menu">
<li><a href="welcome.php">Home Page</a></li>
<li><a href="welcome.php?4">Leaves Information</a></li>
<li><a href="welcome.php?3">Attendance Record</a></li>
<li><a href="welcome.php?1">Change Password</a></li>
<li><a href="index.php">LOGOUT</a></li>
</ul>
</br>
<?php
	session_start();
	$username = $_SESSION['id'];
	if($username == 0){
			header("location:index.php");
		}
	include("config.php");
	include("ot.php");
	$sql = "SELECT * FROM staff WHERE staff_id = '$username'";
	//$sql2 = "SELECT machine.name as location,machine.machine_no FROM inout,machine WHERE inout.machine_no = machine.machine_no AND inout.staff_id = '$username'";
	$curr_year = date("Y");
	$sql3 = "SELECT * FROM leaves WHERE staff_id = '$username' AND date_part('year',fromdate) = '$curr_year'";
	$result = pg_query($connection, $sql);
	//$result2 = pg_query($connection, $sql2);
	$result3 = pg_query($connection, $sql3);
	echo "<table width=100% bgcolor=#F0F7F7 id='css_ltb'>";
	while($row3 = pg_fetch_object($result3))
		{
			$a_p = $row3->a_p;
			$fromdate = date("j",strtotime($row3->fromdate));
			$todate = date("j",strtotime($row3->todate));
			if (($todate - $fromdate)==0){
					if ($a_p>0){
						$number_leaves = 1/2;
						}
					else{
						$number_leaves = 1;
						}
					}
			else{
					if($a_p>0){
						$number_leaves = $todate - $fromdate - 0.5;
					}
					else{
						$number_leaves = $todate - $fromdate;
					}
				}
			$subtotalleaves = $subtotalleaves + $number_leaves;
		}
	while($row = pg_fetch_object($result))
		{
			 	$startdate = $row->startdate;
				$year = date("Y") - date("Y", strtotime($startdate));
				if ($year <= 5){
						if($year ==0){
								$totalleaves = 12 - date("n", strtotime($startdate));
								}
						else{
								$totalleaves = 12;
							}
						}
				elseif((6<=$year) && ($year<=9)){
						$totalleaves = 14;
						}
				else {
						$totalleaves = 16;
					}
		$left_leavesday = $totalleaves - $subtotalleaves;
			 echo "<tr>";
			 echo "<td>Staff ID :</td>";
			 echo "<td>".$row->staff_id."</td>";
			 echo "</tr>";
			 echo "<tr>";
			 echo "<td>Name :</td>";
			 echo "<td>".$row->name."</td>";
			 echo "</tr>";
			 echo "<tr>";
			echo "<td>START WORKING DAY : &nbsp&nbsp</td>";
			echo "<td>&nbsp&nbsp</td>";
			echo "</tr>";
			echo "<tr>";
			 echo "<td>LEAVES OF ".(date("Y")-1)."&nbsp : &nbsp</td>";
			 echo "<td>&nbsp</td>";//$totalleaves is in here
			 echo "</tr>";
			 echo "<tr>";
			 echo "<td>LEAVES OF ".date("Y")."&nbsp : &nbsp</td>";
			 echo "<td>&nbsp</td>";//$totalleaves is in here
			 echo "</tr>";
		}
	//while($row2 = pg_fetch_object($result2))
	//	{
	//		$location = $row2->location;
	//		$machineno = $row2->machine_no;
	//	}
	//echo "<tr>";
	//echo "<td>Location :</td>";
	//echo "<td>".$location."</td>";
	//echo "</tr>";
	echo "</table>";
	//bang thong tin thoi diem data duoc cap nhat
	$sql_thoidiem = "select checktime from inout where staff_id = '$username' order by checktime desc limit 1";
	$result_thoidiem = pg_query($connection, $sql_thoidiem);
	$data_thoidiem = pg_fetch_array($result_thoidiem);
	echo "<table width=100% bgcolor=#F0F7F7 id='css_ltb'>";
	echo "<tr>";
	echo "<td><b>Last Update :</b></td>";
	$last_update = $data_thoidiem["checktime"];
	echo "<td><b>".date("j/n/Y G:i:s",strtotime($data_thoidiem["checktime"]))."</b></td>";
	echo "</tr>";
	echo "</table>";
	// note ghi chu
	echo "<table width=100% bgcolor=#F0F7F7 id='css_ltb'>";
	echo "<tr>";
	echo "<td><p>";
	echo "<b>THIS WEBSITE IS IN BETA STAGE</b></br>
	<font size='1.5'>ALL COMMENTS ARE HIGHLY WELCOME</br>
	PLEASE SEND BY EMAIL EITHER TO:</br>
           &nbsp&nbsp&nbsp&nbsp&nbsp&nbspDANG-DUC-HUNG@KINDEN.COM.VN</br>
           &nbsp&nbsp&nbsp&nbsp&nbsp&nbspNGHIEM-BA-HIEU@KINDEN.COM.VN</br>
           &nbsp&nbsp&nbsp&nbsp&nbsp&nbspTRUONG-QUANG-THANH@KINDEN.COM.VN</br>
	</font>
	<b>FUTURE FUNCTIONS</b></br>
           &nbsp&nbsp&nbsp&nbsp&nbsp&nbspASSIGN/REGISTER LEAVE</br>
           &nbsp&nbsp&nbsp&nbsp&nbsp&nbspASSIGN/REGISTER OVERTIME</br>
      
	<b>KINDEN VIETNAM IT TEAM</b>";
	echo "</p></td>";
	echo "</tr>";
	echo "</table>";
?>
</td>
<td width = "80%" valign = "top">
<!-- Content  -->
<?php
session_start();
include_once('att_record.php');
include_once('detail_leaves.php');
$QS = $_SERVER["QUERY_STRING"];
$month = date('n');
if ($QS == 1) // password
       {
	include("change_pass.php");
    }
elseif($QS == 2){// print attendance record
	/*Print attendance record */
	$staffid = $_SESSION['id'];
	att_view($staffid,$month);
}
elseif($QS == 3){//download attendance record sheet
	//include_once('phpToPDF.php');
	$staffid = $_SESSION['id'];
	att_view($staffid,$month);
}
elseif($QS==4){
	//thong tin ngay phep
	$staffid = $_SESSION['id'];
        view_leaves($staffid);
}
else // current Month
        {
	$staffid = $_SESSION['id'];
	att_view_main($staffid);
	}
?>
</td>
</tr>
 <tr width = "100%" bgcolor='#C7E2E2'>
  <td colspan = 2>KINDEN VIETNAM IT TEAM</td>
 </tr>
</table>
</body>
</html>


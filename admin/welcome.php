<html>
<head>
<title>Attendance Record Management</title>
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
ATTENDANCE RECORD MANAGEMENT
</h2>
</font>
</td>
</tr>
<tr width ="100%">
<td width = "15%" valign ="top">
<ul id="css_vertical_menu">
<li><a href="welcome.php">Main Menu</a></li>
<li><a href="welcome.php?1">Staff Information</a></li>
<li><a href="welcome.php?12">Statistic attendance</a></li>
<li><a href="welcome.php?2">Holiday</a></li>
<li><a href="welcome.php?3">Leave</a></li>
<li><a href="welcome.php?31">Statistic Leave Data</a></li>
<li><a href="welcome.php?4">OverTime For Group</a></li>
<li><a href="welcome.php?5">IN-OUT For Group</a></li>
<li><a href="welcome.php?6">Print attendance record</a></li>
<li><a href="index.php">LOGOUT</a></li>
</ul>
</td>
<td width = "85%" valign = "top">
				<!-- Content  -->
							<?php
							session_start();
							$manhanvien = $_SESSION['id'];
							if($manhanvien == 0){
								header("location:index.php");
							}
							include("config.php");
							include("ot.php");
							$QS = $_SERVER["QUERY_STRING"];
							$month = $_POST['month'];
							if ($QS == 1)
								   {
									include('userinfo.php');
									}
							elseif ($QS == 2)
								 {
									include('holiday.php');
								 }
							elseif ($QS == 12)
								 {
									include('statistic.php');
								 }
							elseif($QS == 13){
								 include('search_att.php');
							}
							elseif($QS == 4){
								 include('overtime.php');
							}
							elseif($QS == 5){
								  include('in_out_for_group.php');
							}
							elseif($QS == 31){
								include('leaves_statistic.php');
							}
							elseif($QS == 6){
								include('print_attendance.php');
							}
							elseif($QS == 3){
							      include('leaves.php');
							}
							else
								{
									//include('leaves.php');
									echo '<center><img src="image/hr.jpg" width="600" height="500"></center>';
								}
							?>
				<!-- Content  -->
</td>
</tr>
<tr width = "100%" bgcolor='#C7E2E2'>
  <td colspan='2'>Kinden Viet Nam IT Team</td>
 </tr>
</table>
</body>
</html>


<link rel="stylesheet" type="text/css" href="table.css"/>
<?php
session_start();
$manhanvien = $_SESSION['id'];
if($manhanvien == 0){
	header("location:index.php");
}
include('config.php');
include("ot.php");
$staffid = $_GET['id'];
//$name = $_GET['name'];
$sql_get_name = "select name from staff where staff_id = '$staffid'";
$result_get_name = pg_query($connection,$sql_get_name);
$row_name = pg_fetch_array($result_get_name);
$name = $row_name['name'];
$month = $_GET['month'];
if(isset($_POST['view_att'])){
       if($_POST['currentmonth'] == 'curr_month'){
       $staffid = $_POST['staffid'];
       $name = $_POST['staffname'];
       $month = date('n');
       }
       else{
	      $staffid = $_POST['staffid'];
	      $name = $_POST['staffname'];
	      $month = $_POST['optionmonth']; 
       }
	   include('form_view_att_for_cal_overtime.php');
}
if(isset($_POST['search'])){
	$staffid = $_POST['staffid'];
	$sql_get_name = "select name from staff where staff_id = '$staffid'";
	$result_get_name = pg_query($connection,$sql_get_name);
	$row_name = pg_fetch_array($result_get_name);
	$name = $row_name['name'];
}
echo '<b>Detail Working Time Of </b></br></br>';
echo '<form name = "att_record" method="post" action="search_att.php">';
echo '<table width="100%" border = "0">';
echo '<input type="hidden" name="month" value="'.$month.'">';
echo '<label>Staff ID: </label><input type="text" name="staffid" value= "'.$staffid.'">';
echo '<input type="submit" name="search" value="Search...">';
echo '<br>';
echo '<label>Staff Name: </lable><input type="text" name="staffname" value = "'.$name.'" readonly = 1><br></br>';
echo '<label>Choose a month : </label>';
echo '<select name ="optionmonth">';
echo '<option value = "1">1</option>';
echo '<option value = "2">2</option>';
echo '<option value = "3">3</option>';
echo '<option value = "4">4</option>';
echo '<option value = "5">5</option>';
echo '<option value = "6">6</option>';
echo '<option value = "7">7</option>';
echo '<option value = "8">8</option>';
echo '<option value = "9">9</option>';
echo '<option value = "10">10</option>';
echo '<option value = "11">11</option>';
echo '<option value = "12">12</option>';
echo '</select>';
echo '<input type="radio" name="currentmonth" value="curr_month"><label> Current month</label>';
echo '&nbsp&nbsp&nbsp<input type = "submit" name="view_att" value="View att to calculate overtime">';
echo '</table>';
echo '</form>';
echo '<hr>';
	  view_att_form($month,$staffid);
?>

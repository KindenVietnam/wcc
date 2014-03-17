<script type = "text/javascript">
  function openURL_edit(sURL){ 
             window.open(sURL,"Window1","menubar=no,width=400,height=300,toolbar=no"); 
          } 
</script> 
<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<h2><left>Holidays</left></h2>
<?php
							session_start();
							$manhanvien = $_SESSION['id'];
							if($manhanvien == 0){
								header("location:index.php");
							}
include_once("config.php");
$del = $_GET['del'];
                echo '<form name = "form1" method="post" action = "welcome.php?2">';
                echo '<lable>Day :&nbsp&nbsp</lable><input type="text" name="day" size="10" onfocus="JavaScript:showCalendarControl(this);"></br>';
                echo '<lable>Note :&nbsp&nbsp</lable><input type="text" name="note" ></br>';
		echo '<input type="checkbox" name = "sh">Special holiday</br>';
		echo '<input type="checkbox" name = "ch">Company holiday</br>';
		echo '<input type="checkbox" name = "th">Tet holiday</br>';
                echo '<input type="submit" name="add" value="Add">';
		echo '<table width="60%" border = "0">';
                echo "<tr bgcolor='#C7E2E2'>";
                echo "<td>HoliDay</td>";
                echo "<td>Note</td>";
		echo "<td>Type of Holiday</td>";
                echo "<td>Control</td>";
                echo "</tr>";
                $sql_str = "SELECT * FROM public_holiday order by holiday DESC";
                $result=pg_query($connection, $sql_str);
                while($res=pg_fetch_array($result)){
                          $bien = $res['id'];
							echo "<tr>";
							echo "<td>".$res['holiday']."</td>";
							echo "<td>".$res['note']."</td>";
							echo "<td>".$res['sh']."</td>";
	echo "<td><a href=\"JavaScript:openURL_edit('edit_holiday.php?id1=$bien')\">Edit</a> |";
    echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='welcome.php?2&&id=$bien&&del=1';}\">Delete</a></td>";
							echo "</tr>";
                       }
                echo '</table>';
                echo '</form>';
if(isset($_POST['add']))
{
	
	$day=$_POST['day'];
	$note = $_POST['note'];
	$sh = $_POST['sh'];
	$th = $_POST['th'];
	$ch = $_POST['ch'];
	$type = '';
	if($sh == true){
	  $type = 'sh';
	}
	if($th == true){
	  $type = 'th';
	}
	if($ch == true){
	  $type = 'ch';
	}
	// checking empty fields
	if(empty($day) || empty($note))
	{
		header('location:welcome.php?2');
	}
	else // if all the fields are filled (not empty)
	{	
		//insert data to database
		$sql_insert = "INSERT INTO public_holiday(holiday,note,sh) VALUES('$day','$note','$type')";
		$result=pg_query($connection, $sql_insert);
		header("location:welcome.php?2");
	}
}
if($del==1){
         $id = $_GET['id'];
	 $sql_del = "DELETE FROM public_holiday where id ='$id'";
         $result=pg_query($connection, $sql_del);
          header("Location:welcome.php?2");
          }
$db->close();
?>

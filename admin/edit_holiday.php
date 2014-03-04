<?php
include_once("config.php");
$id1 = $_GET['id1'];
$sql = "SELECT * FROM public_holiday where id ='$id1'";
    $result=pg_query($connection, $sql);
                while($row=pg_fetch_array($result)){
                        $id=$row['id'];
                        $day =$row['holiday'];
	                $note =$row['note'];
		}
echo '<h2><left>Edit Holiday</left></h2>';
echo '<form name="form1" method="post" action="edit_holiday.php">';
echo '<table border="0">';
echo '<tr>'; 
echo '<td>ID</td>';
echo '<td><input type="text" name="id" value="'.$id1.'"></td>';
echo '</tr>';
echo '<tr>'; 
echo '<td>Day</td>';
echo '<td><input type="text" name="day" value="'.$day.'"></td>';
echo '</tr>';
echo '<tr>'; 
echo '<td>Note</td>';
echo '<td><textarea name="note" cols="25" rows="5">'.$note.'</textarea></td>';
echo '</tr>';
echo '<tr>';
echo '<td><input type="submit" name="update" value="Update"></td>';
echo '<td><input type="button" value="Close" onclick="JavaScript:window.close()"></td>';
echo '</tr>';
echo '</table>';
echo '</form>';
if(isset($_POST['update']))
{
	
	$id=$_POST['id'];
	$day=$_POST['day'];
	$note = $_POST['note'];		
       pg_query($connection, "update public_holiday set id = '$id',holiday = '$day',note='$note' where id = '$id'");
		echo "<font color='green'>Data edit successfully.";
	
}
?>
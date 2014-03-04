<script type = "text/javascript">
  function openURL_add(sURL) { 
             window.open(sURL,"Window1","menubar=no,width=600,height=500,toolbar=no"); 
          }
  function openURL_working(sURL) { 
             window.open(sURL,"Window1","menubar=no,width=1200,height=700,toolbar=no,scrollbars=yes"); 
          } 
</script> 
<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<?php
echo '<form name="form1" method="post" action="welcome.php?1">';
echo '<table>';
echo '<tr>';
echo '<td>';
echo '<table>';
echo '<tr>';
echo '<td>';
echo "<h3>Search Staff's Information</h3>";
echo '<input type="radio" name="op" value="op_id">Staff ID<br>';
echo "<input type='radio' name='op' value='op_name'>Staff's Name<br>";
echo '<input type="text" name="txttim"><input type="submit" name="search" value="Search">';
echo '</td></tr>';
echo '<tr><td>';
$sql_machine = "select * from machine";
$result_machine = pg_query($connection, $sql_machine);
echo '<select name="machine_id">';
echo '<option value="Company ID">Select Location ... </option>';
while ($row1 = pg_fetch_object($result_machine)){
			echo '<option value="' .$row1->machine_no. '">';
			echo $row1->name;
			echo '</option>';
	}
echo '</select>';
echo '<input type="submit" name="filter" value="Filter">';
echo '</td>';
echo '</tr>';
echo '</table>';
echo '</td>';
echo '<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>';
echo '</tr>';
echo '</table>';
echo '</form>';
echo '<hr>';
if(isset($_POST['search'])){
$op = $_POST['op'];
$tim = $_POST['txttim'];
if ($op == "op_id"){
$sql_tim = "select staff_id,name,startdate from staff where staff_id='$tim'";
}
else{
		$sql_tim = "select staff_id,name,startdate from staff where name ilike '%$tim%'";
	}
echo '<h3>Staff Information</h3>';
                echo '<table width="100%" border = "0">';
                echo "<tr bgcolor='#C7E2E2'>";
                echo "<td>Staff ID</td>";
                echo "<td>Name</td>";
		echo "<td>Start Working Day</td>";
		echo "<td>Total leaves day</td>";
                echo "<td>Control</td>";
                echo "</tr>";
                $result = pg_query($connection, $sql_tim);
				while($row = pg_fetch_array($result)){
					$year = date("y") - date("y", strtotime($row['startdate']));
					if ($year <= 5){
							if($year ==0){
									$totalleaves = 12 - date("n", strtotime($row['startdate']));
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
					$bien = $row['staff_id'];
                    echo "<tr>";
	                echo "<td>".$row['staff_id']."</td>";
			$name = $row['name'];
                    echo "<td>".$row['name']."</td>";
					echo "<td>".$row['startdate']."</td>";
					echo "<td>".$totalleaves."</td>";
	echo "<td><a href=\"JavaScript:openURL_working('search_att.php?id=$bien&name=$name')\">Detail Working</a> |";
	echo "<a href=\"JavaScript:openURL_add('detail_user.php?id1=$bien')\">Detail User</a> |";
        echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='welcome.php?1&&id=$bien&&del=1';}\">Delete</a></td>";
                    echo "</tr>";
                    }
                echo '</table>';
}
if(isset($_POST['filter'])){
$machine_no = $_POST['machine_id'];
$sql_filter = "select staff.staff_id as staff_id,staff.name as name,staff.startdate as startdate,machine.name as machine_name, count(staff.staff_id) as total_staff
from staff,inout,machine
where
	staff.staff_id = inout.staff_id and machine.machine_no = inout.machine_no
	and machine.machine_no = '$machine_no'
group by staff.staff_id,machine.machine_no
order by staff.staff_id asc;";
echo '<h3>Staff Information</h3>';
                echo '<table width="100%" border = "0">';
                echo "<tr bgcolor='#C7E2E2'>";
				echo "<td>Location</td>";
                echo "<td>Staff ID</td>";
                echo "<td>Name</td>";
				echo "<td>Start Working Day</td>";
				echo "<td>Total leaves day</td>";
                echo "<td>Control</td>";
                echo "</tr>";
                $result = pg_query($connection, $sql_filter);
				while($row = pg_fetch_array($result)){
					$year = date("y") - date("y", strtotime($row['startdate']));
					if ($year <= 5){
							if($year ==0){
									$totalleaves = 12 - date("n", strtotime($row['startdate']));
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
					$bien = $row['staff_id'];
                    echo "<tr>";
					echo "<td>".$row['machine_name']."</td>";
	                echo "<td>".$row['staff_id']."</td>";
			$name = $row['name'];
                    echo "<td>".$row['name']."</td>";
					echo "<td>".$row['startdate']."</td>";
					echo "<td>".$totalleaves."</td>";
    echo "<td><a href=\"JavaScript:openURL_working('search_att.php?id=$bien&name=$name')\">Detail Working</a> |";
    echo "<a href=\"JavaScript:openURL_add('detail_user.php?id1=$bien')\">Detail Leaves</a> |";
    echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='welcome.php?1&&id=$bien&&del=1';}\">Delete</a></td>";
    echo "</tr>";
    }
    echo '</table>';
}
?>
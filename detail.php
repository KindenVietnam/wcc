<link rel="stylesheet" type="text/css" href="table.css"/>
<?php
session_start();
include("config.php");
$username = $_SESSION['id'];
$month = $GET['month'];
echo $month;
echo "<table width='100%' border=0 id='tb1'>";
echo "<tr bgcolor='#A9BCF5'>";
echo "<td>Date</td>";
echo "<td>Weekday</td>";
echo "<td>IN</td>";
echo "<td>OUT</td>";
echo "</tr>";
$day_month = date('j');
for ($i=1;$i<=$day_month;$i++)
     {
$c_date = date("Y-n-$i");
$sql1 = "select staff_id,date(checktime) as workday,
       to_char(min(checktime), 'HH24:MI:SS') as intime,
      to_char(max(checktime), 'HH24:MI:SS') as outtime
from inout
where staff_id = '$username' and date(checktime) = '$c_date'
group by staff_id,workday
order by workday";
$wday = date("D",strtotime($c_date));
$result1 = pg_query($connection, $sql1);
$row1 = pg_fetch_object($result1);
$in = $row1->intime;
$out = $row1->outtime;
	echo "<tr>";
	echo "<td>".$c_date."</td>";
	echo "<td>".$wday."</td>";
	echo "<td>".$in."</td>";
	echo "<td>".$out."</td>";
	echo "</tr>";
   }
echo "</table>";
?>

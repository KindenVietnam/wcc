<script type = "text/javascript">
  function openURL_add(sURL) { 
             window.open(sURL,"Window1","scrollbars=1,width=700,height=1000"); 
          } 
</script> 
<link rel = "stylesheet" type="text/css" href = "table.css"/>
<?php
							session_start();
							$manhanvien = $_SESSION['id'];
							if($manhanvien == 0){
								header("location:index.php");
							}
include_once("config.php");
//lay so ngay cua mot thang
$month = $_GET['month'];
if($month != 0){
  $curr_month = $month;
}
else{
    $curr_month = date("n");
}
$thanghientai = date("n");
$lastmonth = date("n")-1;
$curr_year = date("Y");
$curr_day = date("j");
$day_in_month = cal_days_in_month(CAL_GREGORIAN, $curr_month, $curr_year); 
$total_rows = $day_in_month;
echo "<a href='welcome.php?12&month=$lastmonth'><< Last Month</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
echo "<a href='welcome.php?12&month=$thanghientai'><< Current Month >></a>";
echo "<table width='100%' id='tabletime'>";
echo "<tr>";
echo "<td rowspan='2'><b>Location</b></td>";
echo "<td colspan='31'><center><b>".$curr_month."-".date("Y",strtotime($curr_year))."</b></center></td>";
echo "</tr>";
// in so ngay thanh hang
echo "<tr>";
for($i=1;$i<=$total_rows;$i++)
{
	if($i == date("j"))
	{
	    if($i < 10){ // them so 0 dang truoc nhung ngay nho hon 10
		echo "<td bgcolor = '#819FF7' width='10%'>"."0".$i."</td>";
	    }
	    else{
	      echo "<td bgcolor = '#819FF7' width='10%'>".$i."</td>";
	    }
	}
	else
	{
	  if($i < 10){ // them so khong dang truoc nhung ngay nho hon 10
		echo "<td width = '10%'>"."0".$i."</td>";
	  }
	  else{
	    echo "<td width = '10%'>".$i."</td>";
	  }
	}
}
echo "</tr>";
$sql_danhsachduan = "select machine_no,name from machine where status <> 0";
$result = pg_query($connection, $sql_danhsachduan);
/* tao bang */
while($rows = pg_fetch_array($result))
{
    echo "<tr>";
    $machineno = $rows['machine_no'];
    echo "<td>".$rows['name']."</td>"; //tao cot chua ma vung
    for($column = 1; $column <= $total_rows ; $column ++)
    {
      $c_date = date("Y-$curr_month-$column");
      
		//$date_to_compare = date("Y-n-$column");
		$sql_thongke = "select count(staff_id) as tongso from inout where date(checktime) = '$c_date' and machine_no = '$machineno'";
        $ketqua = pg_query($connection, $sql_thongke);
		$row_ketqua = pg_fetch_array($ketqua);
		$tongso = $row_ketqua["tongso"];
		if($tongso > 0 )
			{
			echo "<td bgcolor = '#0080FF'><a href=\"JavaScript:openURL_add('detail_att.php?day=$c_date&location=$machineno')\">F</a></td>";
			}
		else{
			echo"<td bgcolor = '#F78181'>&nbsp</td>";
			}
    }
    echo "</tr>";
}
echo "</table>";
?>
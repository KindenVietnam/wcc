<link rel="stylesheet" type="text/css" href="table.css"/>
<head>
<script type = "text/javascript">
  function openURL_add(sURL) { 
             window.open(sURL,"Window1","menubar=no,width=600,height=500,toolbar=no"); 
          }
  function openURL_working(sURL) { 
             window.open(sURL,"Window1","menubar=no,width=1200,height=700,toolbar=no,scrollbars=yes"); 
          } 
</script> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Leaves Statistic</title>
</head>
<body>
<h2>Kinden Vietnamese Staff's (Balance of Leaves)</h2>
<form name="form1" method="post" action="welcome.php?31">
  <label>All Staff</label><input name="radiobutton" type="radio" value="allstaff">
  </br>
  <label>Staff ID
  <input name="txtstaffid" type="text" id="txtstaffid">
  </label>
  <input type="submit" name="Submit" value="Search">
</form>
<?php
include("config.php");
$status = $_POST['radiobutton'];
$manhanvien = $_POST['txtstaffid'];
if($status == 'allstaff'){
    $sql_leaves_statistic = "select * from staff order by staff_id";
}
else{
  $sql_leaves_statistic = "select * from staff where staff_id = '$manhanvien'";
}
$result_leaves_statistic = pg_query($connection, $sql_leaves_statistic);
$result_leaves_count = pg_query($connection, $sql_leaves_statistic);
?>
<table width="100%" border="0" id= 'mtb'>
  <tr>
    <td width="70" rowspan="2"><div align="center"><strong>Staff No</strong> </div></td>
    <td width="165" rowspan="2"><div align="center"><strong>Name</strong></div></td>
    <td width="70" rowspan="2"><div align="center"><strong>Start working date </strong></div></td>
    <td colspan="13"><div align="center"><strong>Year of <?php echo date('Y')?></strong></div></td>
    <td width="70" rowspan="2"><div align="center"><strong>Blnc. <?php echo date('Y'); ?> </strong></div></td>
    <td width="70" rowspan="2"><div align="center"><strong>Year of . <?php echo date('Y');?></strong></div></td>
    <td width="70" rowspan="2"><div align="center"><strong>Blnc. <?php echo date('Y')-1;?> </strong></div></td>
    <td width="70" rowspan="2"><div align="center"><strong>Control</strong></div></td>
  </tr>
 <tr>
    <td width="30"><div align="center"><strong>L</strong></div></td>
    <td width="30"><div align="center"><strong>Jan</strong></div></td>
    <td width="30"><div align="center"><strong>Feb</strong></div></td>
    <td width="30"><div align="center"><strong>Mar</strong></div></td>
    <td width="30"><div align="center"><strong>Apr</strong></div></td>
    <td width="30"><div align="center"><strong>May</strong></div></td>
    <td width="30"><div align="center"><strong>Jun</strong></div></td>
    <td width="30"><div align="center"><strong>Jul</strong></div></td>
    <td width="30"><div align="center"><strong>Aug</strong></div></td>
    <td width="30"><div align="center"><strong>Sep</strong></div></td>
    <td width="30"><div align="center"><strong>Oct</strong></div></td>
    <td width="30"><div align="center"><strong>Nov</strong></div></td>
    <td width="30"><div align="center"><strong>Dec</strong></div></td>
  </tr>
  <?php
  
 // while($row_leaves_count = pg_fetch_array($result_leaves_count)){
   //     count_leaves($row_leaves_count['staff_id']);
 // }
  while($row_leaves_statistic = pg_fetch_array($result_leaves_statistic)){
    echo '<tr>';
    echo '<td width="70" ><div align="center">'.$row_leaves_statistic['staff_id'].'</div></td>';
    $bien = $row_leaves_statistic['staff_id'];
    echo '<td width="165" ><div align="center"><input type = "text" name = "staffname" value = "'.$row_leaves_statistic['name'].'"></div></td>';
    echo '<td width="70" ><div align="center">'.$row_leaves_statistic['startdate'].'</div></td>';
    $startdate = $row_leaves_statistic['startdate'];
    $lastleaves_year = $row_leaves_statistic['lastyear_leaves'];
    $year = date("Y") - date("Y", strtotime($startdate));
	  if ($year <= 5){
                  if($year ==0){
				  $leaves_curryear = 12 - date("n", strtotime($startdate));
				}
		  else{
			  $leaves_curryear = 12;
		      }
	      }
          elseif((6<=$year) && ($year<=9)){
					    $leaves_curryear = 14;
					  }
	  else {
		  $leaves_curryear = 16;
		}
          if(date("Y-n-j") <= date("Y-06-21")){
                $phepnamtruoc = $lastleaves_year;
            }
          else{
                 $phepnamtruoc = 0;
          }
    $totalleaves = $leaves_curryear + $phepnamtruoc;
    $tongngayphepdanghi = ($row_leaves_statistic['jan']+$row_leaves_statistic['feb']+$row_leaves_statistic['mar']+$row_leaves_statistic['apr']+
    $row_leaves_statistic['may'] + $row_leaves_statistic['jun']+$row_leaves_statistic['jul']+$row_leaves_statistic['aug']+$row_leaves_statistic['sep']+
    $row_leaves_statistic['oct']+$row_leaves_statistic['nov']+$row_leaves_statistic['dec']);
    echo '<td width="20"><div align="center"><input type = "text" name = "l" value = "'.$row_leaves_statistic['l'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "jan" value = "'.$row_leaves_statistic['jan'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "feb" value = "'.$row_leaves_statistic['feb'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "mar" value = "'.$row_leaves_statistic['mar'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "apr" value = "'.$row_leaves_statistic['apr'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "may" value = "'.$row_leaves_statistic['may'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "jun" value = "'.$row_leaves_statistic['jun'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "jul" value = "'.$row_leaves_statistic['jul'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "aug" value = "'.$row_leaves_statistic['aug'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "sep" value = "'.$row_leaves_statistic['sep'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "oct" value = "'.$row_leaves_statistic['oct'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "nov" value = "'.$row_leaves_statistic['nov'].'" size="3"></div></td>';
    echo '<td width="20"><div align="center"><input type = "text" name = "dec" value = "'.$row_leaves_statistic['dec'].'" size="3"></div></td>';
    echo '<td width="70" ><div align="center">'.($totalleaves - $tongngayphepdanghi).'</div></td>';
    echo '<td width="70" ><div align="center">'.$totalleaves.'</div></td>';
    echo '<td width="70" ><div align="center">'.$phepnamtruoc.'</div></td>';
    echo "<td width='70' ><a href=\"JavaScript:openURL_add('detail_user.php?id1=$bien')\">Edit</a></td>";
    echo '</tr>';
  }
 ?>
</table>
</body>
</html>
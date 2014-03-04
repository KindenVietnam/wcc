<link href="calendar.css" rel="stylesheet" type="text/css">
<script src="calendar.js" language="javascript"></script>
<?php
function view_leaves($manv){
include("config.php");
$staffid = $manv;
$sql_loc = "select * from staff where staff_id = '$staffid'";
$result = pg_query($connection, $sql_loc);
while($hang = pg_fetch_array($result)){
	$staffid = $hang['staff_id'];
	$name = $hang['name'];
	$email = $hang['email'];
	$startdate = $hang['startdate'];
	$lastleaves_year = $hang['lastyear_leaves'];
	$year = date("Y") - date("Y", strtotime($startdate));
					if ($year <= 5){
							if($year ==0){
									$curryear_leaves = 12 - date("n", strtotime($startdate));
									}
							else{
									$curryear_leaves = 12;
								}
							}
					elseif((6<=$year) && ($year<=9)){
							$curryear_leaves = 14;
							}
					else {
							$curryear_leaves = 16;
						}
}
echo '<form name="form_edit" method="post" action="detail_user.php">';
echo '<h3>List of Leaves days in '.date("Y").'</h3>';
echo '<table name="list_leaves" border="0">';
		echo "<tr bgcolor='#C7E2E2'>";
                echo "<td>From </td>";
                echo "<td>To </td>";
		echo "<td>Number of leaves day</td>";
		echo "<td>Reason </td>";
                echo "<td>Status </td>";
                echo "</tr>";
                $sql_str = "SELECT * FROM leaves WHERE staff_id='$staffid' AND date_part('year', fromdate) = date_part('year', current_date) order by todate asc";
                $result=pg_query($connection, $sql_str);
                while($res=pg_fetch_array($result)){
							$bien = $res['id'];
							$a_p = $res['a_p'];
							$st_trunggian = $res['status'];
							$fromdate = date("j",strtotime($res['fromdate']));
							$todate = date("j",strtotime($res['todate']));
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
												$number_leaves = (($todate - $fromdate)+1) - 0.5;
												}
										else{
												$number_leaves = ($todate - $fromdate)+1;
											}
							    }
							// moc thoi gian tinh phep
							$mocthoigian = date("Y-06-21");
							if(mktime($res['todate']) <= mktime($mocthoigian)){
								$tongngaydanghiphep_trong6thang = $tongngaydanghiphep_trong6thang + $number_leaves;
							}
							else{
								 $tongngaydanghiphep_ngoai6thang = $tongngaydanghiphep_ngoai6thang + $number_leaves;
							}
							if($st_trunggian == 'y'){
								$status_ = "applied";
							}
							else
							{
								$status_ = "not yet";
							}
							$subtotalleaves = $tongngaydanghiphep_ngoai6thang + $tongngaydanghiphep_trong6thang;
							echo "<tr>";
							echo "<td>".$res['fromdate']."</td>";
							echo "<td>".$res['todate']."</td>";
							echo "<td>".$number_leaves."</td>";
							echo "<td>".$res['reason']."</td>";
							echo "<td>".$status_."</td>";
//echo "<td><a href=\"JavaScript:openURL_edit('edit_leaves.php?id1=$bien')\">Edit</a> |";
//echo "<a href=\"JavaScript:if(confirm('Confirm Delete?')==true){window.location='del_leaves.php?id=$bien&staffid=$staffid';}\">Delete</a></td>";
							echo "</tr>";
                }
		if($mocthoigian == date("Y-n-j")){
			$lastleaves_year = 0;
		}
		if($tongngaydanghiphep_trong6thang <= $lastleaves_year){
		   $ngayphepconlai_namtruoc = $lastleaves_year - $tongngaydanghiphep_trong6thang;
		   $tongsongayphepconlai = $ngayphepconlai_namtruoc + ($curryear_leaves - $tongngaydanghiphep_ngoai6thang);
		}
		else{
		  $ngayphepconlai_namtruoc = 0;
		  $tongsongayphepconlai = $curryear_leaves - (($tongngaydanghiphep_trong6thang - $lastleaves_year)+$tongngaydanghiphep_ngoai6thang);
		}
				//echo '<tr>';
				//echo '<td colspan="2">';
				//echo 'Total Leaves from ' . date("21-1-Y") . ' To '. date("21-6-Y").' : ';
				//echo '</td>';
				//echo '<td>'.$tongngaydanghiphep_trong6thang.'</td>';
				//echo '</tr>';
				//echo '<tr>';
				//echo '<td colspan="2">';
				//echo 'Total Leaves from ' . date("22-6-Y") . ' To 21-1-'.(date("Y")+1) . ' : ';
				//echo '</td>';
				//echo '<td>'.$tongngaydanghiphep_ngoai6thang.'</td>';
				//echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Total Leaves in :' .(date("Y"));
				echo '</td>';
				echo '<td>'.$subtotalleaves.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Year ' . (date("Y")-1);
				echo '</td>';
				echo '<td>'.$lastleaves_year.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Balance Year ' . (date("Y")-1);
				echo '</td>';
				echo '<td>'.$ngayphepconlai_namtruoc.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Year ' . date("Y");
				echo '</td>';
				echo '<td>'.($curryear_leaves + $lastleaves_year).'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="2">';
				echo 'Balance Year ' . date("Y");
				echo '</td>';
				echo '<td>'.$tongsongayphepconlai.'</td>';
				echo '</tr>';


echo '</table>';
echo '</form>';
}
?>
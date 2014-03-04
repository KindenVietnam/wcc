<?php
function count_leaves($manhanvien){
include('config.php');
$sql_str = "SELECT * FROM leaves WHERE staff_id='$manhanvien' AND date_part('year', fromdate) = date_part('year', current_date) AND date_part('month', fromdate) = date_part('month', current_date)";
$result=pg_query($connection, $sql_str);
$subtotalleaves = 0;
while($res=pg_fetch_array($result)){
				    $bien = $res['id'];
				    $a_p = $res['a_p'];
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
							$number_leaves = $todate - $fromdate - 0.5;
							}
					    else{
							$number_leaves = $todate - $fromdate;
						}
                                    }
				$subtotalleaves = $subtotalleaves + $number_leaves;
}
$curr_month = date('M');
switch($curr_month){
    case 'Jan':{
        $sql_update_count_leaves = "update staff set jan = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Feb':{
        $sql_update_count_leaves = "update staff set feb = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Mar':{
        $sql_update_count_leaves = "update staff set mar = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Apr':{
        $sql_update_count_leaves = "update staff set apr = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'May':{
        $sql_update_count_leaves = "update staff set mar = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Jun':{
        $sql_update_count_leaves = "update staff set jun = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Jul':{
        $sql_update_count_leaves = "update staff set jul = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Aug':{
        $sql_update_count_leaves = "update staff set aug = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Seb':{
        $sql_update_count_leaves = "update staff set seb = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Oct':{
        $sql_update_count_leaves = "update staff set oct = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Nov':{
        $sql_update_count_leaves = "update staff set nov = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
    case 'Dec':{
        $sql_update_count_leaves = "update staff set dec = '$subtotalleaves' where staff_id = '$manhanvien'";
        break;
    }
}
pg_query($connection,$sql_update_count_leaves);
}
?>
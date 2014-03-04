<?php
	$curr_month = date('n');
	include_once('config.php');
    include('save_to_temp_table.php');
	$sql_staffid = "select staff_id from staff where staff_id >= '1000' and staff_id <= '1999'";
	$result_staffid = pg_query($connection, $sql_staffid);
	while($row_staffid = pg_fetch_array($result_staffid)){
			$manhanvien = $row_staffid['staff_id'];
			save_to_temp($manhanvien,$curr_month);
		}
?>

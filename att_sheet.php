<link rel="stylesheet" type="text/css" href="table.css"/>
<?php
include('att_record.php');
$staffid = $_GET['id'];
$month = $_GET['month'];
att_view($staffid,$month);
?>
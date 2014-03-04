<?php
include("config.php");
$id = $_GET['id'];
$staffid = $_GET['staffid'];
$sql_del = "delete from leaves where id = '$id'";
$ketqua = pg_query($connection, $sql_del);
header("Location:welcome.php?3&id=$staffid");
?>
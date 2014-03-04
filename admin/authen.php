<?php
session_start();
include("config.php");
if(isset($_POST['login']))
{
$staffid=$_POST['username']; 
$mypassword=$_POST['password']; 
$sql_auth = "select count(name) as id,role from staff where staff_id = '$staffid' and pass = '$mypassword' group by role";
$result_ivs = pg_query($connection, $sql_auth);
$id_row = pg_fetch_array($result_ivs);
if(($id_row["id"] > 0)&&($id_row["role"] == 1))
		{
			$_SESSION['id'] = $staffid;
			header("location:welcome.php");
		}
else{
        header("location:index.php");
     }
}
?>
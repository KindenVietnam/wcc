<center>
<form action="index.php" method="post" >
<table width = "30%" border="0">
<tr>
<img src="image/login.jpg" width="304" height="228">
</tr>
<tr align = "center"><td>
<label>StaffID :</label></td>
<td><input type="text" name="username"/></td>
</tr>
<tr align="center"><td>
<label>Password :</label></td>
<td><input type="password" name="password"/></td>
</tr>
<tr><td colspan ="2" align = "center">
<input type="submit" name ="login" value=" Login "/>
<input type="button" value=" Cancel " onclick = "JavaScript:window.close()"/>
</td>
</tr>
</table>
</form>
</center>

<?php
session_start();
include("config.php");
if(isset($_POST['login']))
{
$staffid=$_POST['username']; 
$mypassword=$_POST['password']; 
$sql_auth = "select count(name) as id from staff where staff_id = '$staffid' and pass = '$mypassword'";
$result_ivs = pg_query($connection, $sql_auth);
$id_row = pg_fetch_array($result_ivs);
if($id_row["id"] > 0)
		{
			$_SESSION['id'] = $staffid;
			header("location:welcome.php");
		}
else{
        header("location:index.php");
     }
}
?>

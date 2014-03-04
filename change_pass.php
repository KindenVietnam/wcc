<center>
<form action="welcome.php?1" method="post" >
<table width = "30%" border="0">
<tr align = "center"><td>
<label>New Password :</label></td>
<td><input type="password" name="newp1"/></td>
</tr>
<tr align="center"><td>
<label>Confirm Password :</label></td>
<td><input type="password" name="newp2"/></td>
</tr>
<tr><td colspan ="2" align = "center">
<input type="submit" name ="change" value=" Save change "/>
<input type="submit" name ="sadg" value="Cancel"/>
</td>
</tr>
</table>
</form>
</center>
<?php
session_start();
include("config.php");
$username = $_SESSION['id'];
if(isset($_POST['change']))
{
$newp1=$_POST['newp1']; 
$newp2=$_POST['newp2']; 
 if (strcmp($newp1,$newp2)==0){
            $sql_changepass = "update staff set pass = '$newp1' where staff_id = '$username'";
            $result = pg_query($connection,$sql_changepass);
            if(!$result){
               echo "<center><a href='welcome.php?1'>Error to change password</a></center>";
            }
            else{
                echo"<center><a href='welcome.php'>Successfull to change password !</a></center>";
            }
	}
else{
        echo "<center><a href='welcome.php?1'>Error to change password</a></center>";
     }
}
?>
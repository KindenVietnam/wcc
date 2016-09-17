<?php
// Database connection
// Please declare PostgreSQL username and password here
$host = "localhost";
$user = "user";
$pass = "password";
$db = "db_name";
// open a connection to the database server
$connection = pg_connect ("host=$host dbname=$db user=$user password=$pass");
if (!$connection)
{
 die("Could not open connection to database");
}

?>


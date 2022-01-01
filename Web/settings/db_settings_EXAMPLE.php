<?php
$db_host = "DB_IP";
$db_user = "DB_USER";
$db_password = "DB_PASSWORD";
$db_name = "DB_NAME";

// Create connection
$con = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($con->connect_error) {
  die("Connection to the database failed : " . $con->error);
}
?>
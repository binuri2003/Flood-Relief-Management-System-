<?php

$host = "localhost";
$user = "root";
$pass = "";
$database = "flood_relief_management_system";

$conn = mysqli_connect($host,$user,$pass,$database);

if(!$conn){
    die("Database Connection Failed" . mysqli_connect_error());
}

?>

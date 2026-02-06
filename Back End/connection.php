<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost:3307"; 
$user = "root";
$pass = "";
$dbname = "flood_relief_management_system";

try {
    $conn = mysqli_connect($host, $user, $pass, $dbname);
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
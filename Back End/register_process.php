<?php
include 'connection.php';

$fname = $_POST['first_name'];
$lname = $_POST['last_name'];
$addr  = $_POST['address'];
$uname = $_POST['username'];
$pass  = $_POST['password'];
$role  = $_POST['role'];

try {
    $sql = "INSERT INTO user (FirstName, LastName, Address, UserName, Password, Role) 
            VALUES ('$fname', '$lname', '$addr', '$uname', '$pass', '$role')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../Front End/login.html?status=success");
        exit();
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        header("Location: ../Front End/register.html?status=exists");
        exit();
    } else {
        echo "Database Error: " . $e->getMessage();
    }
}
mysqli_close($conn);
?>
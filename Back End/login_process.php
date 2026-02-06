<?php
session_start();
include 'connection.php';

$uname = $_POST['username'];
$pass  = $_POST['password'];

$uname = mysqli_real_escape_string($conn, $uname);
$pass = mysqli_real_escape_string($conn, $pass);

$sql = "SELECT * FROM user WHERE UserName='$uname' AND Password='$pass'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $row['UserID'];
    $_SESSION['role'] = $row['Role'];

    if ($row['Role'] == 'Admin') {
        header("Location: ../adminpart/adminFrontend.html");
        exit();
    } else {
        header("Location: ../userpart/user_home.html");
        exit();
    }
} else {
    header("Location: ../Front End/login.html?status=error");
    exit();
}
?>

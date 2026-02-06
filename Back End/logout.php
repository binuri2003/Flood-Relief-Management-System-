<?php
session_start();

session_unset();
 
session_destroy();

header("Location: ../Front End/login.html?message=loggedout");
exit();
?>
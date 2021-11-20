<?php
    session_start();
    session_unset();
    unset($_SESSION['userID']);
    unset($_SESSION['fname']);
    session_destroy();
    header("Location: index.php");
    exit;
?>
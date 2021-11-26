<?php

include 'db.php';
if (!isset($_SESSION)) {
    session_start();
}
$userID = $_SESSION["userID"];
clearNotifications($userID);

function clearNotifications($userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("DELETE FROM notifications WHERE userID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
        }
        $stmt->close();
    }
    $conn->close();
    
    header("Location:".$_SERVER["HTTP_REFERER"]);
}

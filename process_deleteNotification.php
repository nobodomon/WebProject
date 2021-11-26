<?php

include "db.php";
if(!isset($_SESSION)){
    session_start();
}

$notificationID = $_GET["notificationID"];

deleteNotification($notificationID,$_SESSION['userID']);
function deleteNotification($notificationID, $userID) {
    //Create database connection
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        //Prepare the statement:
        $stmt = $conn->prepare("DELETE FROM notifications WHERE notificationID = ? AND userID = ?");
        //Bind & Execute the query statement:
        $stmt->bind_param("ii", $notificationID, $userID);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        } else {
            $success = true;
        }
        $stmt->close();
    }
    $conn->close();
    //do redirect.
    header("Location:".$_SERVER["HTTP_REFERER"]);
}
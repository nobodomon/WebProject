<?php

session_start();
$commentID = $_GET['commentID'];
$currUser = $_SESSION['userID'];
$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
if ($conn->connect_error) {
    $errorMsg = "Connect failed " . $conn->connect_error;
} else {
    $stmt = $conn->prepare("DELETE FROM comments WHERE commentID =? AND authorID =?");
    $stmt->bind_param("ii", $commentID, $currUser);
    if (!$stmt->execute()) {
        $errorMsg = "Statement failed " . $stmt->error;
    } else {
        
    }
    $stmt->close();
}
$conn->close();
header("Location:" . $_SERVER["HTTP_REFERER"]);
?>


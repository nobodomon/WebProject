<?php

include "db.php";
session_start();
$postID = $_GET['postID'];
$redirect = $_GET['redirectTo'];
$commentingUserID = $_SESSION['userID'];
$comment = htmLawed($_POST['comment']);
$postedDate = date_create()->format('Y-m-d H:i:s');
$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
$stmt = $conn->prepare("INSERT INTO comments (postID, authorID, comment,postedDate) VALUES (?,?,?,?)");
$stmt->bind_param("iiss", $postID, $commentingUserID, $comment, $postedDate);
$stmt->execute();
$stmt->close();
$conn->close();

$authorID = $_GET['userID'];
$content = "has commented on your post!";
processNotifications($authorID, $content, 1, $commentingUserID, $postID);

header("Location:" . $_SERVER["HTTP_REFERER"]);
?>


<?php

session_start();
$postID = $_GET['postID'];
$commentingUserID = $_SESSION['userID'];
$comment = $_POST['comment'];
$postedDate = date_create()->format('Y-m-d H:i:s');
$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
$stmt = $conn -> prepare("INSERT INTO comments (postID, authorID, comment,postedDate) VALUES (?,?,?,?)");
$stmt-> bind_param("iiss", $postID, $commentingUserID, $comment,$postedDate);
$stmt->execute();
$stmt->close();
$conn->close();



$authorPageID = getAuthorOfPost($postID)['author_id'];

header("Location: profile.php?userID=$authorPageID")

?>


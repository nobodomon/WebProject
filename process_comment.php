<?php

session_start();
$postID = $_GET['postID'];
$redirect = $_GET['redirectTo'];
$commentingUserID = $_SESSION['userID'];
$comment = $_POST['comment'];
$postedDate = date_create()->format('Y-m-d H:i:s');
$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
$stmt = $conn->prepare("INSERT INTO comments (postID, authorID, comment,postedDate) VALUES (?,?,?,?)");
$stmt->bind_param("iiss", $postID, $commentingUserID, $comment, $postedDate);
$stmt->execute();
$stmt->close();
$conn->close();

$authorID = $_GET['userID'];

if ($redirect == 1) {
    header("Location: viewPost.php?postID=$postID");
} else if($redirect == 2){
    header("Location: index.php");
}else {
    header("Location: profile.php?userID=$authorID");
}
?>

